<template>
<div class="notification-activity-item" :class="{'unread': !item.is_read}" @click="targetClick">
	<div class="row mx-0">
		<div class="col-2 pl-2 pr-0 text-left">
			<img class="avatar" :src="this.item.activities[0].actorImg">
		</div>
		<div class="col-10 px-2 text-left">
			<div v-if="item.verb == 'follow'">
				<strong>{{ actors }}</strong> {{ verbDisplay }}
			</div>
			<div v-else-if="item.verb == 'promote'">
				"{{ titleDisplay }}" {{ verbDisplay }} {{ item.updated_at | moment("D MMM YYYY") }}
			</div>
			<div v-else>
				<strong>{{ actors }}</strong> {{ verbDisplay }} "{{ titleDisplay }}"
			</div>
			<small class="text-secondary">{{ item.updated_at | moment("add", "7 hours") | moment("from", "now") }}</small>
		</div>
	</div>
</div>
</template>

<script>
export default {
	props: ['item'],
	computed: {
		actors() {
			var actorList = []
			this.item.activities.forEach( activity => {
				if (!actorList.includes(activity.actorName)) {
					actorList.push(activity.actorName)
				}
			})

			var actors = ''
			for (var i = 0; i < 3 && i < actorList.length; i++){
				var name = actorList[i]
				if (actors == '') {
					actors += name
				} else {
					actors += ', ' + name
				}
			}

			if (actorList.length > 3) {
				return actors + ' และอีก ' + (actorList.length - 3) + 'คน'
			} else {
				return actors
			}
		},
		avatars() {
			var avatarList = []
			this.item.activities.forEach( activity => {
				if (!avatarList.includes(activity.actorImg)) {
					avatarList.push(activity.actorImg)
				}
			})
			
			if (avatarList.length > 3) {
				return avatarList.slice(0, 2)
			} else {
				return avatarList
			}
		},
		object() {
			return this.item.activities[0].object
		},
		verbDisplay() {
			switch (this.item.verb) {
				case 'love':
					if (this.item.activities[0].object.includes("COMMENT")) {
						return 'ถูกใจความเห็นของคุณใน '
					} else {
						return 'ถูกใจ'
					}
					break;
				case 'comment':
					if (this.item.activities[0].object.includes("COMMENT")) {
						return 'ตอบกลับความเห็นของคุณใน '
					} else {
						return 'แสดงความคิดเห็นใน'
					}
					break;
				case 'follow':
					return 'เริ่มติดตามคุณ'
					break;
				case 'promote':
					return 'ถูกจัดอยู่ในกลุ่ม Hot Now ประจำวันที่ '
					break;
				default:
					return this.item.verb
			}
		},
		titleDisplay() {
			if(this.item.activities[0].targetTitle) {
				if(this.item.activities[0].targetTitle.length > 40) {
					return this.item.activities[0].targetTitle.substr(0, 40) + '...'
				} else {
					return this.item.activities[0].targetTitle
				}
			} else {
				return null
			}
		}
	},
	methods: {
		targetClick() {
			this.$emit('linkClick', this.item.id)
		}
	},
}
</script>

<style lang="scss">
.notification-activity-item {
	background: #FFF;
	border: 1px solid #EEE;
	margin: 5px 2px;
	font-size: 12px;
	padding: 5px 0;
	min-height: 50px;
	&.unread {
		background: #c9d5dd;
	}

	&:hover {
		cursor: pointer;
	}

	.avatar {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		border: 1px solid #EEE;
		display: inline-block;
	}
}
</style>