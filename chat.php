<html>
<head>
	<link rel="stylesheet" type="text/css" href="./Styles/chat.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script>  
	function showMessage(messageHTML) {
		$('#chat-box').append(messageHTML);
	}

	$(document).ready(function(){
		var websocket = new WebSocket("ws://localhost:8092/server.php"); 
		websocket.onopen = function(event) { 
			showMessage("<div class='chat-connection-ack'>Connection is established!</div>");		
		}
		websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
			showMessage("<div class='"+Data.message_type+"'>"+Data.message+"</div>");
			$('#chat-message').val('');
		};
		
		websocket.onerror = function(event){
			showMessage("<div class='error'>Problem due to some Error" + event + "</div>");
		};
		websocket.onclose = function(event){
			showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
		}; 
		
		$('#frmChat').on("submit",function(event){
			event.preventDefault();
			$('#chat-user').attr("type","hidden");		
			var messageJSON = {
				chat_user: $('#chat-user').val(),
				chat_message: $('#chat-message').val()
			};
			websocket.send(JSON.stringify(messageJSON));
		});
	});
	</script>
	</head>
	<body>
		<form name="frmChat" id="frmChat">
			<div id="chat-box"></div>
			<input type="text" name="chat-user" id="chat-user" placeholder="Name" class="input" required />
			<input type="text" name="chat-message" id="chat-message" placeholder="Message"  class="input message-box" required />
			<input type="submit" id="btnSend" class="send-btn"name="send-chat-message" value="Send" >
		</form>
</body>
</html>