import Echo from "laravel-echo"

 window.io = require('socket.io-client');

 window.Echo = new Echo({
     broadcaster: 'socket.io',
     host: window.location.hostname + ':6041'
 });
 var userId=window.Data.user_id;
 var unread_notifications=window.Data.unread_notifications;
//  console.log(unread_notifications)
 window.Echo.private('babysitters-notification.' + userId)
    .notification((notification) => {
        console.log(notification);
        $('.notification_list').prepend('<a class="d-flex" href="'+notification.route+'">'+
        '<div class="media d-flex align-items-start">'+
            '<div class="media-left">'+
            '<i class="feather icon-shopping-cart font-medium-5 primary"></i>'+
            '</div>'+
            '<div class="media-body">'+
                    '<p class="media-heading"><span class="font-weight-bolder">'+notification.title+'</p>'+
                    '<small class="notification-text">'+ (notification.body.substring(0,100) + notification.sender_data.fullname)+'</small>'+
                     '</div>'+
            '</div></a>'
            );
        $('.notification_list #no_notifications').remove();
        let newNotification = notification.message;
        unread_notifications.unshift(newNotification)
       $('.notify_count').text(unread_notifications.length);
    });
