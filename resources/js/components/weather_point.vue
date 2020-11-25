<template>
<div class="weather-point-row" :class="{odd: odd}">
  <span>{{ point.date }}</span>
  <span>{{ point.location.city }}</span>
  <span>{{ point.location.state }}</span>
  <span>{{ point.location.lat }}</span>
  <span>{{ point.location.lon }}</span>
  <span class="temps">
    <span v-for="temp in point.temperature">{{ temp }}</span>
  </span>
</div>
</template>

<script>
export default {
    name: 'WeatherPoint',

    props: {
        point: {
            type: Object,
            required: true
        },
        odd: Boolean
    }
}
</script>

<style lang="scss" scoped>
.weather-point-row {
    display: flex;
    flex-wrap: wrap;
    background-color: #ccc;

    &.odd {
        background-color: #eee;
    }

    span {
        display: inline-block;
        padding: 0.2rem 0.5rem;
    }

    .temps {
        span {
            padding: 0 0 0 0.5rem;
        }
    }
}


</style>
