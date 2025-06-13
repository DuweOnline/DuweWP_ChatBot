var openchat = document.getElementById("openchat");
var chatbot = document.getElementById("chatbot");
openchat.addEventListener("click", function(e) {
	chatbot.classList.toggle("active");
	StateMachine.render();
})
var StateMachine = {
	currentState: "",
	states: {},
	interact: function() {},
	render: function() {}
}

StateMachine.interact = function(option) {
	var currentState = this.states[this.currentState]
	var selectedOption = currentState.options[option]
	if (selectedOption.href) { window.open(selectedOption.href); return; }
	this.currentState = selectedOption.next;
	this.render();
}
StateMachine.render = function() {
	var message = document.getElementById("messagetext")
	var chatbot_buttons = document.getElementById("chatbot_buttons")
	var currentState = this.states[this.currentState];
	message.innerHTML = "<div class='typing'><span></span><span></span><span></span></div>";
	chatbot_buttons.innerHTML = '';
	setTimeout(() => {
		message.innerHTML = currentState.message;
		chatbot_buttons.innerHTML = "";
		currentState.options.forEach((option, i) => {
			chatbot_buttons.innerHTML += '<a href="javascript:StateMachine.interact('+i+');" class="chatbot_button">'+option.title+'</a>';
		});
	}, 500);
	return;
}