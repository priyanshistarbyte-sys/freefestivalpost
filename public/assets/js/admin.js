$(document).ready(function() {
    // Sidebar dropdown toggle
    $('.dropdown-toggle').click(function(e) {
        e.preventDefault();
        
        const parent = $(this).parent();
        const isOpen = parent.hasClass('open');
        
        // Close all dropdowns
        $('.has-dropdown').removeClass('open');
        
        // Toggle current dropdown
        if (!isOpen) {
            parent.addClass('open');
        }
    });

    // Sidebar toggle for mobile
    $('.sidebar-toggle').click(function() {
        $('.sidebar').toggleClass('open');
    });

    // Close sidebar when clicking outside on mobile
    $(document).click(function(e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('.sidebar, .sidebar-toggle').length) {
                $('.sidebar').removeClass('open');
            }
        }
    });

    // Handle window resize
    $(window).resize(function() {
        if ($(window).width() > 768) {
            $('.sidebar').removeClass('open');
        }
    });
});