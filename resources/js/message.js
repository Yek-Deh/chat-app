const selectedContact = $('meta[name="selected_contact"]');
const authId = $('meta[name="auth_id"]').attr('content');
const baseUrl = $('meta[name="base_url"]').attr('content');
const inbox = $('.messages ul');

function toggleLoader() {
    $('.loader').toggleClass('d-none');
}

function messageTemplate(text, className, time) {
    return `<li class="${className}"><img src="${baseUrl}/images/avatar.png" alt=""/><p>${text}<sub>${time}</sub></p></li>`
}

function formatTime(createdAt) {
    // const messageTime = new Date(createdAt);
    // return messageTime.getHours().toString().padStart(2, '0') + ':' +
    //     messageTime.getMinutes().toString().padStart(2, '0');

    //with seconds
    const messageTime = new Date(createdAt);
    const hours = messageTime.getHours().toString().padStart(2, '0');
    const minutes = messageTime.getMinutes().toString().padStart(2, '0');
    const seconds = messageTime.getSeconds().toString().padStart(2, '0');

    return `${hours}:${minutes}:${seconds}`;
}

function scrollToLastMessage() {
    const messagesContainer = $('.messages');
    // Scroll to the last message by scrolling to the bottom of the container
    messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
}

function fetchMessages() {
    let contactId = selectedContact.attr('content');

    $.ajax({
        method: 'GET',
        url: baseUrl + '/fetch-messages',
        data: {
            contact_id: contactId
        },
        beforeSend: function () {
            toggleLoader();
        },
        success: function (data) {
            setContactInfo(data.contact);
            //append messages
            inbox.empty();
            // Function to format the time as "H:i"

            data.messages.forEach(value => {
                const formattedTime = formatTime(value.created_at); // Format time
                if (value.from_id == contactId) {

                    inbox.append(messageTemplate(value.message, 'sent', formattedTime));
                } else {
                    // let time=value.created_at.format('H:i');
                    inbox.append(messageTemplate(value.message, 'replies', formattedTime));
                }
            });
            //  $('.preview').text(data.lastMessage);
            scrollToLastMessage();
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
            toggleLoader();
        }

    })
}

function sendMessage() {
    let messageBox = $('.message-box');
    let contactId = selectedContact.attr('content');
    let formData = $('.message-form').serialize();
    $.ajax({
        method: 'POST',
        url: baseUrl + '/send-message',
        data: formData + '&contact_id= ' + contactId,
        beforeSend: function () {
            let message = messageBox.val();
            let time = new Date();
            time = formatTime(time);
            inbox.append(messageTemplate(message, 'replies', time));
            messageBox.val('');
        },
        success: function (data) {
        },
        error: function (xhr, status, error) {
        }
    })
    scrollToLastMessage();
}

function setContactInfo(contact) {
    $('.contact-name').text(contact.name);
}

$(document).ready(function () {
    $('.contact').on('click', function () {
        let contactId = $(this).data('id');
        selectedContact.attr('content', contactId);

        //hide the blank wrap
        $('.blank-wrap').addClass('d-none');
        // fetch messages
        fetchMessages();
    });
    $('.message-form').on('submit', function (e) {
        e.preventDefault();
        sendMessage();
    })
});

//listen to the live events
window.Echo.private('message.' + authId)
    .listen('SendMessageEvent', (e) => {
        const formattedTime = formatTime(e.time); // Format time
        if (e.from_id == selectedContact.attr('content')) {
            inbox.append(messageTemplate(e.text, 'sent', formattedTime));
        }

    });
