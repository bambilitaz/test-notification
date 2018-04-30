<template>
<div>
	<div class="bell mx-auto">
		<img class="-icon" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTPsQns36-cnbyIUGql0KFZcehc8TmZBZmePW96-8iN4IwA5ZiYXQ">
		<div v-if="counter > 0" class="-counter rounded-circle">10</div>
	</div>
</div>
</template>

<script>
export default {
	data() {
		return {
			counter: 0,
			token: ''
		}
	},
	mounted () {
		//generateToken
		axios.get('/api/token/99')
		.then((response) => {
			this.token = response.data
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
		transform: rotate(15deg);
	}
}
</style>