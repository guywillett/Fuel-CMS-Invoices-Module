$(document).ready(function(){
    $('#send_note_yes').click(function(){
        var msg = ''
        if($('#note_sent').val() == 'no'){
            msg = 'When you save this Invoice record, email notifications WILL be sent out'
            alert(msg)
        } else {
            msg = 'You have already sent this Invoice before: Do you want to resend it?'
            var r = confirm(msg)
            if(r == true){
                $('#note_sent').val('no').parent('.value').prepend('send again/')
            }
        }

    });

    $('#send_note_no').click(function(){
        var msg = ''
        msg = 'When you save this Invoice record, email notifications WILL NOT be sent out'
        alert(msg)
    });
});