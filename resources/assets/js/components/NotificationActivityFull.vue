<template>
<div class="notification-activity-item" :class="{'unread': !item.is_read}" @click="targetClick">
	<div class="row">
		<div class="col-2 pr-0 text-left">
			<img class="avatar" v-for="(ava, index) in avatars" :key="index" :src="ava">
		</div>
		<div class="col-10 text-left">
			<div v-if="item.verb == 'follow'">
				<strong>{{ actors }}</strong> {{ verbDisplay }}
			</div>
			<div v-else-if="item.verb == 'promote'">
				"{{ item.activities[0].targetTitle }}" {{ verbDisplay }} {{ item.updated_at | moment("D MMM YYYY") }}
			</div>
			<div v-else>
				<strong>{{ actors }}</strong> {{ verbDisplay }} "{{ item.activities[0].targetTitle }}"
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
			var actors = ''
			for (var i = 0; i < 3 && i < this.item.activities.length; i++){
				var activity = this.item.activities[i]
				if (actors == '') {
					actors += activity.actorName
				} else {
					actors += ', ' + activity.actorName
				}
			}

			return actors
		},
		avatars() {
			var avatars = []
			for (var i = 0; i < 3 && i < this.item.activities.length; i++){
				var activity = this.item.activities[i]
				avatars.push(activity.actorImg)
			}

			return avatars
		},
		object() {
			return this.item.activities[0].object
		},
		verbDisplay() {
			switch (this.item.verb) {
				case 'love':
					if (this.item.activities[0].object.includes("COMMENT")) {
						return 'ถูกใจความเห็นของคุณ ใน '
					} else {
						return 'ถูกใจ'
					}
					break;
				case 'comment':
					if (this.item.activities[0].object.includes("COMMENT")) {
						return 'ตอบกลับความเห็นของคุณ จาก '
					} else {
						return 'แสดงความคิดเห็น ใน'
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
	padding: 5px;
	min-height: 50px;
	&.unread {
		background: #c9d5dd;
	}

	&:hover {
		cursor: pointer;
	}

	.avatar {
		width: 32px;
		height: 32px;
		border-radius: 50%;
		border: 1px solid #EEE;
		display: inline-block;

		& + .avatar {
			margin-left: -22px;
		}
	}
}
</style>