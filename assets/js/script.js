// assets/js/script.js

// Toast Notification System
function showToast(message, type = 'success') {
    const icon = type === 'success' ? 'check-circle' : 'alert-circle';
    const toast = $(`
        <div class="toast-msg toast-${type}">
            <i data-lucide="${icon}" class="toast-icon"></i>
            <span style="font-size: 14px; font-weight: 500;">${message}</span>
        </div>
    `);
    
    $('#toast-container').append(toast);
    lucide.createIcons();
    
    // Animate in
    setTimeout(() => {
        toast.addClass('show');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

$(document).ready(function() {
    // Sidebar Toggle (Desktop)
    $('#toggle-sidebar').click(function() {
        $('.sidebar').toggleClass('collapsed');
        // Toggle icon
        const icon = $(this).find('i');
        if($('.sidebar').hasClass('collapsed')) {
            icon.attr('data-lucide', 'chevron-right');
        } else {
            icon.attr('data-lucide', 'chevron-left');
        }
        lucide.createIcons();
    });

    // Mobile Sidebar Toggle
    $('#mobile-menu-btn, #mobile-overlay').click(function() {
        $('.sidebar').toggleClass('mobile-open');
        $('#mobile-overlay').toggleClass('show');
    });

    // Dark Mode Toggle
    $('#theme-toggle').click(function() {
        $('body').toggleClass('dark-mode');
        const isDark = $('body').hasClass('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        // Toggle icon
        const icon = $(this).find('i');
        if(isDark) {
            icon.attr('data-lucide', 'sun');
        } else {
            icon.attr('data-lucide', 'moon');
        }
        lucide.createIcons();
    });

    // Check saved theme
    if (localStorage.getItem('theme') === 'dark') {
        $('body').addClass('dark-mode');
        $('#theme-toggle i').attr('data-lucide', 'sun');
        lucide.createIcons();
    }
    
    // Toggle Task Status via AJAX
    $(document).on('click', '.task-checkbox', function(e) {
        e.preventDefault();
        const btn = $(this);
        const taskId = btn.data('id');
        const taskItem = btn.closest('.task-item');
        
        // Optimistic UI update
        const isDone = btn.hasClass('done');
        if(isDone) {
            btn.removeClass('done').html('');
            taskItem.removeClass('done');
        } else {
            btn.addClass('done').html('<i data-lucide="check" style="width: 14px; height: 14px;"></i>');
            taskItem.addClass('done');
            lucide.createIcons();
        }
        
        // Send AJAX request
        $.ajax({
            url: '../pages/toggle_task.php',
            method: 'POST',
            data: { id: taskId },
            dataType: 'json',
            success: function(res) {
                if(!res.success) {
                    // Revert if failed
                    showToast('Failed to update task', 'error');
                    if(isDone) {
                        btn.addClass('done').html('<i data-lucide="check" style="width: 14px; height: 14px;"></i>');
                        taskItem.addClass('done');
                    } else {
                        btn.removeClass('done').html('');
                        taskItem.removeClass('done');
                    }
                    lucide.createIcons();
                } else {
                    // if there's a progress bar, update it
                    if($('#progress-bar-fill').length) {
                        $('#progress-bar-fill').css('width', res.pct + '%');
                        $('#progress-pct-text').text(res.pct + '%');
                    }
                }
            },
            error: function() {
                showToast('Network error', 'error');
            }
        });
    });
    
    // Delete confirm modal (simple implementation)
    $('.delete-task-btn').click(function(e) {
        if(!confirm('Are you sure you want to delete this task?')) {
            e.preventDefault();
        }
    });

});
