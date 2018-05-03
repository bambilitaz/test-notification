<template>
<div>
	<div class="bell mx-auto">
		<img class="-icon" :class="{'ringing': isRinging}" @click="showList" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPsQns36-cnbyIUGql0KFZcehc8TmZBZmePW96-8iN4IwA5ZiYXQ">
		<div v-if="counter > 0" class="-counter rounded-circle">{{ counterDisplay }}</div>
	</div>
	<div class="feed" v-if="isShow && list.length > 0">
		<notification-activity-full v-for="item in list" :key="item.id" :item="item" @linkClick="markRead"></notification-activity-full>
	</div>
</div>
</template>

<script>
export default {
	data() {
		return {
			userFeed: null,
			counter: 0,
			token: '',
			isRinging: false,
			isShow: false,
			list: [],
			newActivities: []
		} 
	},
	computed: {
		notificationId() {
			return this.type == 'full' ? 99 : 66
		},
		counterDisplay() {
			return this.counter > 9 ? '9+' : this.counter
		}
	},
	props: {
		type: {
			type: String,
			default: 'full'
		},
	},
	methods: {
		subscribeFeed() {
			var stream = require('getstream')
			
			var client = stream.connect('dk7dtx3p9pvv', null, '35631')
			//make instance of userFeed
			this.userFeed = client.feed('notification', this.notificationId, this.token)

			var self = this
			function callback(data) {
				console.log(data);
				self.counter += data.new.length
				self.isRinging = true
				setTimeout(() => { self.isRinging = false }, 4000);

				self.toastNotify(data.new)
			}
			function successCallback() {
				console.log('now listening to changes in realtime')
			}
			function failCallback(data) {
				alert('something went wrong, check the console logs')
				console.log(data);
			}
			this.userFeed.subscribe(callback).then(successCallback, failCallback)
		},
		initFeed() {
			this.userFeed.get({limit:10})
			.then((body) => {
				/* on success */
				console.log(body)
				this.list = body.results
				this.counter = body.unseen
			}).catch((reason) => {
				/* on failure, reason.error contains an explanation */
				console.log(reason.error)
			})
		},
		showList() {
			this.isShow = !this.isShow
			//Mark seen
			if(this.isShow) {
				var params = {limit:10, mark_seen: true }
				this.userFeed.get(params)
				.then((body) => {
					console.log('Success! marked seen when open')
					this.counter = 0
					this.list = body.results
				}).catch((reason) => {
					console.log(reason.error)
				})
			}
		},
		markRead(id) {
			var params = { mark_read: [id], mark_seen: [id] }
			this.userFeed.get(params)
			.then((body) => {
				console.log('Success! marked seen & read')
				this.list.find((x) => x.id == id).is_read = true
			}).catch((reason) => {
				console.log(reason.error)
			})
		},
		toastNotify(activities) {
			activities.forEach(n => {
				var icon = ''
				var text = ''

				var title = ''
				if(n.targetTitle.length > 40) {
					title = n.targetTitle.substr(0, 40) + '...'
				} else {
					title = n.targetTitle
				}
				
				var target = n.target.split(':')
				var urlLink = ''
				if(target[0] == 'TOPIC') {
					var urlLink = 'http://www.jeban.com/topic/' + target[1]
				} else if(target[0] == 'PROFILE') {
					var urlLink = 'http://www.jeban.com/mypage/' + target[1]
				}

				text = '<a href="' + urlLink + '" target="_blank">'

				switch(n.verb) {
					case 'love':
						icon = 'heart'
						if (n.object.includes("COMMENT")) {
							text += '<span><strong>' + n.actorName + '</strong> ถูกใจความเห็นของคุณใน "' + title + '"</span>'
						} else {
							text += '<span><strong>' + n.actorName + '</strong> ถูกใจ "' + title + '"</span>'
						}
						break;
					case 'comment':
						if (n.object.includes("COMMENT")) {
							text += '<span><strong>' + n.actorName + '</strong> ตอบกลับความเห็นของคุณใน "' + title + '"</span>'
						} else {
							text += '<span><strong>' + n.actorName + '</strong> แสดงความคิดเห็นใน "' + title + '"</span>'
						}
						icon = 'comment-dots'
						break;
					case 'follow':
						icon = 'user-plus'
						text += '<span><strong>' + n.actorName + '</strong> เริ่มติดตามคุณ' + '</span>'
						break;
					case 'promote':
						icon = 'fire'
						text += '<span><strong>"' + title + '"</strong> ถูกจัดอยู่ในกลุ่ม Hot Now ประจำวัน' + '</span>'
						break;
					default:
						icon = 'info-circle'
						text += '<span><strong>' + n.actorName + '</strong> ' + n.verb + ' on "' + title + '"</span>'
				}

				text += '</a>'

				this.$toasted.show(text, {
					duration: 2000,
					singleton: true,
					icon: icon,
					className: 'notification-toast-activity ' + n.verb,
					containerClass: 'notification-toast-container',
					action: {
						text : 'x',
						onClick : (e, toastObject) => {
							toastObject.goAway(0);
						}
					},
				})
			})
		}
	},
	mounted () {
		//generateToken
		axios.get('/api/token/' + this.notificationId)
		.then((response) => {
			this.token = response.data
			console.log('token:' + this.token)
			
			this.subscribeFeed()
			this.initFeed()
		})
	}
}
</script>

<style lang="scss">
.notification-toast-container {
	&.top-right {
		top: 12%;
		right: 3%;

		@media screen and (max-width: 480px) {
			left: 3%;
		}
	}

	.notification-toast-activity {
		width: 100% !important;
		max-width: 260px !important;
		min-height: 54px !important;
		margin: 0 auto 10px !important;
		padding: 10px 15px !important;
		background: #EFF4F7 !important;
		border: 1px solid #d2dde3 !important;
		border-radius: 3px !important;
		box-shadow: 0 2px 1px rgba(0,0,0,.25) !important;
		justify-content: left !important;
		line-height: 1.4 !important;

		@media screen and (max-width: 480px) {
			max-width: 100% !important;
		}

		.fa {
			margin-right: 15px;
			margin-left: 0;
			font-size: 22px;
			color: #222;
		}

		&.love {
			.fa { color: #ff877f; }
		}
		&.comment {
			.fa { color: #8dc8d7; }
		}
		&.follow {
			.fa { color: #a7c1ec; }
		}
		&.promote {
			.fa { color: #d2483e; }
		}

		a {
			color: #222 !important;
			font-size: 12px !important;
			text-decoration: none !important;

			&:hover {
				text-decoration: none !important;
				cursor: pointer;
			}	
		}

		a.action {
			position: absolute;
			top: -10px;
			right: 10px;
			width: 16px;
			height: 16px;
			display: flex;
			justify-content: center;
			background: #FFF;
			border: 1px solid #DDD;
			border-radius: 50% !important;
			font-size: 9px !important;
			color: #000 !important;
			line-height: 0 !important;
		}
	}
}
.feed {
	position: relative;
	background: #FCFCFC;
	max-width: 320px;
	max-height: 360px;
	overflow: scroll;
	margin: 20px auto;
	border: 1px solid #DDD;
	
	&:before,&:after {
		content: '';
		position: absolute;
		border-style: solid;
		border-width: 0px 12px 12px 12px;
		display: block;
		width: 0;
		z-index: 0;
		left: 0;
		right: 0;
		margin: 0 auto;
	}
	&:before {
		border-color: #DDD transparent;
		top: -12px;
	}
	&:after {
		border-color: #FCFCFC transparent;
		top: -11px;
	}
}
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
		&:hover {
			cursor: pointer;
		}
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