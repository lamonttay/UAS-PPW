-- ============================================================
--  Momentum To-Do List  |  database.sql
--  Created by: Ariq Muhammad
-- ============================================================

CREATE DATABASE IF NOT EXISTS momentum_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE momentum_db;

-- ============================================================
-- TABEL 1: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  email       VARCHAR(150)  NOT NULL UNIQUE,
  password    VARCHAR(255)  NOT NULL,
  avatar      VARCHAR(10)   DEFAULT NULL COMMENT 'Inisial 2 huruf untuk avatar',
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 2: categories
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT          NOT NULL,
  name       VARCHAR(100) NOT NULL,
  color      VARCHAR(7)   DEFAULT '#8b5cf6',
  created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 3: tasks
-- ============================================================
CREATE TABLE IF NOT EXISTS tasks (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT           NOT NULL,
  category_id  INT           DEFAULT NULL,
  title        VARCHAR(200)  NOT NULL,
  description  TEXT          DEFAULT NULL,
  priority     ENUM('low','medium','high') DEFAULT 'medium',
  status       ENUM('pending','completed')  DEFAULT 'pending',
  due_date     DATE          DEFAULT NULL,
  image_path   VARCHAR(255)  DEFAULT NULL,
  completed_at TIMESTAMP     NULL DEFAULT NULL,
  created_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 4: task_logs  (diisi otomatis oleh trigger)
-- ============================================================
CREATE TABLE IF NOT EXISTS task_logs (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  task_id   INT          NOT NULL,
  user_id   INT          NOT NULL,
  action    VARCHAR(50)  NOT NULL COMMENT 'created | updated | completed | deleted',
  note      VARCHAR(255) DEFAULT NULL,
  logged_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- TABEL 5: user_sessions
-- ============================================================
CREATE TABLE IF NOT EXISTS user_sessions (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT          NOT NULL,
  token       VARCHAR(255) DEFAULT NULL,
  ip_address  VARCHAR(45)  DEFAULT NULL,
  user_agent  VARCHAR(255) DEFAULT NULL,
  last_active TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- VIEW 1: Ringkasan task per user
-- ============================================================
CREATE OR REPLACE VIEW v_user_task_summary AS
SELECT
  u.id                                           AS user_id,
  u.name,
  u.email,
  COUNT(t.id)                                    AS total_tasks,
  SUM(t.status = 'completed')                    AS completed_tasks,
  SUM(t.status = 'pending')                      AS pending_tasks,
  SUM(t.status = 'pending' AND t.due_date < CURDATE()) AS overdue_tasks,
  ROUND(
    SUM(t.status = 'completed') / NULLIF(COUNT(t.id), 0) * 100
  , 0)                                           AS completion_rate
FROM users u
LEFT JOIN tasks t ON u.id = t.user_id
GROUP BY u.id, u.name, u.email;

-- ============================================================
-- VIEW 2: Tasks dengan detail kategori (untuk daftar task)
-- ============================================================
CREATE OR REPLACE VIEW v_tasks_detail AS
SELECT
  t.id,
  t.user_id,
  t.title,
  t.description,
  t.priority,
  t.status,
  t.due_date,
  t.completed_at,
  t.created_at,
  t.updated_at,
  c.id   AS category_id,
  c.name AS category_name,
  c.color AS category_color,
  CASE
    WHEN t.status = 'completed'                        THEN 'completed'
    WHEN t.due_date IS NULL                            THEN 'normal'
    WHEN t.due_date < CURDATE()                        THEN 'overdue'
    WHEN t.due_date = CURDATE()                        THEN 'due_today'
    ELSE 'normal'
  END AS task_status_label
FROM tasks t
LEFT JOIN categories c ON t.category_id = c.id;

-- ============================================================
-- FUNCTION 1: Hitung jumlah task selesai milik user tertentu
-- ============================================================
DELIMITER //
DROP FUNCTION IF EXISTS fn_completed_count //
CREATE FUNCTION fn_completed_count(uid INT)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
  DECLARE total INT DEFAULT 0;
  SELECT COUNT(*) INTO total
  FROM tasks
  WHERE user_id = uid AND status = 'completed';
  RETURN total;
END //
DELIMITER ;

-- ============================================================
-- FUNCTION 2: Cek apakah sebuah task sudah overdue (return 1/0)
-- ============================================================
DELIMITER //
DROP FUNCTION IF EXISTS fn_is_overdue //
CREATE FUNCTION fn_is_overdue(due_date DATE, stat VARCHAR(20))
RETURNS TINYINT(1)
DETERMINISTIC
NO SQL
BEGIN
  IF due_date IS NULL THEN
    RETURN 0;
  END IF;
  IF stat = 'completed' THEN
    RETURN 0;
  END IF;
  RETURN (due_date < CURDATE());
END //
DELIMITER ;

-- ============================================================
-- TRIGGER 1: Catat log saat task baru dibuat
-- ============================================================
DELIMITER //
DROP TRIGGER IF EXISTS trg_task_after_insert //
CREATE TRIGGER trg_task_after_insert
AFTER INSERT ON tasks
FOR EACH ROW
BEGIN
  INSERT INTO task_logs (task_id, user_id, action, note)
  VALUES (NEW.id, NEW.user_id, 'created', CONCAT('Task "', NEW.title, '" dibuat'));
END //
DELIMITER ;

-- ============================================================
-- TRIGGER 2: Catat log + set completed_at saat status berubah
-- ============================================================
DELIMITER //
DROP TRIGGER IF EXISTS trg_task_after_update //
CREATE TRIGGER trg_task_after_update
AFTER UPDATE ON tasks
FOR EACH ROW
BEGIN
  -- Jika status berubah menjadi completed
  IF NEW.status = 'completed' AND OLD.status = 'pending' THEN
    INSERT INTO task_logs (task_id, user_id, action, note)
    VALUES (NEW.id, NEW.user_id, 'completed', CONCAT('Task "', NEW.title, '" ditandai selesai'));
  END IF;
  -- Jika task di-edit (selain perubahan status)
  IF OLD.title != NEW.title OR OLD.description != NEW.description
     OR OLD.priority != NEW.priority OR OLD.due_date != NEW.due_date THEN
    INSERT INTO task_logs (task_id, user_id, action, note)
    VALUES (NEW.id, NEW.user_id, 'updated', CONCAT('Task "', NEW.title, '" diperbarui'));
  END IF;
END //
DELIMITER ;

-- ============================================================
-- DATA DUMMY
-- ============================================================

-- User dummy (password: "password123")
INSERT INTO users (name, email, password, avatar) VALUES
('Ariq Muhammad', 'ariq@momentum.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'AM'),
('Demo User',     'demo@momentum.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DU');

-- Kategori untuk user 1
INSERT INTO categories (user_id, name, color) VALUES
(1, 'Kuliah',    '#8b5cf6'),
(1, 'Pribadi',   '#3b82f6'),
(1, 'Belanja',   '#10b981'),
(1, 'Pekerjaan', '#f59e0b');

-- Tasks dummy untuk user 1
INSERT INTO tasks (user_id, category_id, title, description, priority, status, due_date) VALUES
(1, 1, 'Selesaikan Laporan Web Programming', 'Buat laporan praktikum Web Programming dengan Bootstrap', 'high',   'completed', '2026-06-10'),
(1, 1, 'Submit Tugas Statistika',            'Kumpulkan tugas distribusi probabilitas',                'high',   'completed', '2026-06-12'),
(1, 1, 'Persiapkan Presentasi',              'Buat slide PPT untuk presentasi Basis Data',             'medium', 'pending',   '2026-06-20'),
(1, 2, 'Beli Bahan Makanan',                 'Belanja mingguan di supermarket',                        'low',    'pending',   '2026-06-16'),
(1, 1, 'Praktikum Struktur Data',            'Implementasi BFS dan DFS dengan Python',                 'high',   'pending',   '2026-06-18'),
(1, 4, 'Meeting Tim Proyek',                 'Diskusi progress proyek akhir semester',                 'medium', 'pending',   '2026-06-15');