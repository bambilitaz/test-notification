<template>
	<div class="noti-activity">
		<div class="row mb-5">
			<div class="noti-actor">
				Actor ID
				<input type="text" v-model="actorId">
			</div>
		</div>
		<div class="row justify-content-between">
			<div>
				<div class="noti-option">
					<h6>Select Option</h6>
					<input type="radio" value="love" v-model="optionSelect">
					<label for="one">Love</label><br>
					<input type="radio" value="comment" v-model="optionSelect">
					<label for="one">Comment</label><br>
					<input type="radio" value="follow" v-model="optionSelect">
					<label for="one">follow</label><br>
					<input type="radio" value="promote" v-model="optionSelect">
					<label for="one">Promote</label><br>
				</div>
				<br>
				<div class="noti-detail" v-if="optionSelect == 'love' || optionSelect == 'comment'">
					<input type="radio" value="TOPIC" v-model="typeObject">
					<label >Topic</label>
					<input v-if="typeObject == 'TOPIC'" type="text" v-model="objectId" placeholder="Topic ID"><br>
					<input type="radio" value="COMMENT" v-model="typeObject">
					<label>Comment</label>
					<div v-if="typeObject == 'COMMENT'">
						<input type="text" v-model="objectId" placeholder="Comment ID"><br>
						of Topic <br>
						<input type="text" v-model="targetId" placeholder="Topic ID">
					</div>
				</div>
				<div v-else-if="optionSelect == 'follow'">
					User ID <input type="text" v-model="objectId">
				</div>
				<div v-else-if="optionSelect == 'promote'">
					Topic ID <input type="text" v-model="objectId">
				</div>
				<br>
			</div>
			<div>
				<div class="noti-more-option" v-if ="this.type == 'full'">
					<h6>More Option</h6>
					<div class="row justify-content-between">
						<div class="mr-5"> Actor Image Url </div>
						<div> <input type="text" v-model="imgUrl"></div>
					</div>
					<div class="row justify-content-between">
						<div>Actor Username </div>
						<div><input type="text" v-model="username"></div>
					</div>
					<div class="row justify-content-between">
						<div>Topic Title </div>
						<div><input type="text" v-model="topicTitle"></div>
					</div>
				</div>
				<br>
			</div>
		</div>
		<div class="btn btn-primary" v-if="this.type == 'basic'" @click="submit()">Sumbit</div>
		<div class="btn btn-primary" v-if="this.type == 'full'" @click="submitFull()">Sumbit</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			actorId: null,
			optionSelect: null,
			topicId: null,
			objectId: null,
			targetId: null,
			typeObject: null,
			followUserId: null,
			imgUrl: null,
			username: null,
			topicTitle: null,
			typeTarget: null,
		}
	},
	props: ['type'],
	methods: {
		submit: function() {
			if(this.targetId == null) {
				this.targetId = this.objectId
			}
			if(this.optionSelect == 'follow') {
				this.typeTarget = "PROFILE"
				this.typeObject = "PROFILE"
			}else if(this.optionSelect == 'promote'){
				this.typeObject = "TOPIC"
				this.typeTarget = "TOPIC"
			}else {
				this.typeTarget = "TOPIC"
			}
			axios.post('/api/push' ,{
				notiId : 66,
				data : {
					actor : this.actorId,
					verb : this.optionSelect,
					object : this.typeObject + ":" + this.objectId,
					target : this.typeTarget + ":" + this.targetId
				}
			})
		},
		submitFull: function() {
			if(this.targetId == null) {
				this.targetId = this.objectId
			}
			if(this.optionSelect == 'follow') {
				this.typeTarget = "PROFILE"
				this.typeObject = "PROFILE"
				this.topicTitle = null
			}else if(this.optionSelect == 'promote'){
				this.typeObject = "TOPIC"
				this.typeTarget = "TOPIC"
				this.actorId = "System"
			}else {
				this.typeTarget = "TOPIC"
			}
			axios.post('/api/push' ,{
				notiId : 99,
				data : {
					actor : this.actorId,
					verb : this.optionSelect,
					object : this.typeObject + ":" + this.objectId,
					target : this.typeTarget + ":" + this.targetId
				},

				actorImg : this.imgUrl,
				actorName : this.username,
				targetTitle : this.topicTitle
			})
		}
	}
}
</script>

<style lang="scss">
</style>