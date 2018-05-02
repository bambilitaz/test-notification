<template>
<div>
	<div class="bell mx-auto">
		<img class="-icon" :class="{'ringing': isRinging}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPsQns36-cnbyIUGql0KFZcehc8TmZBZmePW96-8iN4IwA5ZiYXQ">
		<div v-if="counter > 0" class="-counter rounded-circle">{{ counter }}</div>
	</div>
</div>
</template>

<script>
export default {
	data() {
		return {
			counter: 0,
			token: '',
			isRinging: false
		} 
	},
	methods: {
		showList() {
			
		}
	},
	mounted () {
		//generateToken
		axios.get('/api/token/2')
		.then((response) => {
			this.token = response.data
			
			var token = response.data

			console.log('token:' + token)
			
			var stream = require('getstream')
			
			var client = stream.connect('dk7dtx3p9pvv', null, '35631')
			var user = client.feed('notification', '2', token)

			var self = this

			function callback(data) {
				console.log(data);
				self.counter += data.new.length
				self.isRinging = true
				setTimeout(() => { self.isRinging = false }, 4000);
			}

			function successCallback() {
				console.log('now listening to changes in realtime')
			}

			function failCallback(data) {
				alert('something went wrong, check the console logs')
				console.log(data);
			}

			user.subscribe(callback).then(successCallback, failCallback)
		})

		
	}
}
</script>

<style lang="scss">
.bell {
	width: 50px;
	position: relative;

	.-counter {
		width: 24px;
		height: 24px;
		background: #EE0022;
		color: #FFF;
		font-size: 14px;
		position: absolute;
		top: 0;
		right: -5px;
	}
	.-icon {
		width: 100%;
		&.ringing {
			animation: ring 5s .8s ease-in-out infinite;
			transform-origin: 50% 4px;
		}
	}
	@keyframes ring {
		0% { transform: rotate(0); }
		1% { transform: rotate(30deg); }
		3% { transform: rotate(-28deg); }
		5% { transform: rotate(34deg); }
		7% { transform: rotate(-32deg); }
		9% { transform: rotate(30deg); }
		11% { transform: rotate(-28deg); }
		13% { transform: rotate(26deg); }
		15% { transform: rotate(-24deg); }
		17% { transform: rotate(22deg); }
		19% { transform: rotate(-20deg); }
		21% { transform: rotate(18deg); }
		23% { transform: rotate(-16deg); }
		25% { transform: rotate(14deg); }
		27% { transform: rotate(-12deg); }
		29% { transform: rotate(10deg); }
		31% { transform: rotate(-8deg); }
		33% { transform: rotate(6deg); }
		35% { transform: rotate(-4deg); }
		37% { transform: rotate(2deg); }
		39% { transform: rotate(-1deg); }
		41% { transform: rotate(1deg); }

		43% { transform: rotate(0); }
		100% { transform: rotate(0); }
	}
}
</style>