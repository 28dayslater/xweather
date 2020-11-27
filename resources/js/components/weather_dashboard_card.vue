<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">My Weather dashboard</div>

          <div class="card-body">
            <h1>Weather Data</h1>
            <form class="controls" novalidate>
              <h6>Filters</h6>
              <Input
                v-model="startDate"
                type="text"
                ref="startDate"
                label="Start Date"
              />
              <Input
                v-model="endDate"
                type="text"
                ref="endDate"
                label="End Date"
              />
              <Input v-model="lat" type="text" ref="lat" label="Latitude" />
              <Input v-model="lon" type="text" ref="lan" label="Longitude" />
              <button
                type="button"
                class="btn btn-sm btn-primary"
                @click="filterData"
              >
                Filter
              </button>
              <button
                type="button"
                class="btn btn-sm btn-secondary"
                @click="showAddNewForm"
              >
                Add New Point
              </button>

              <WPModal ref="wpModal" @saved="onSaved" />
            </form>
            <div v-for="(wp, idx) in weatherPoints">
              <WeatherPoint
                :point="wp"
                :odd="idx % 2 === 0"
                @click.native="clickWP(idx)"
              />
            </div>
            <div v-if="weatherPoints.length === 0">No data found</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Input from "./input.vue";
import WeatherPoint from "./weather_point.vue";
import WPModal from "./wpmodal.vue";
import moment from "moment";

export default {
  components: {
    Input,
    WeatherPoint,
    WPModal,
  },

  data() {
    return {
      startDate: "",
      endDate: "",
      lat: "",
      lon: "",
      weatherPoints: [],
      showModal: false,
      editedWp: {},
      blankPoint: {
        date: "",
        location: {
          city: "",
          state: "",
          lat: "",
          lon: "",
        },
        temperature: new Array(24).fill(""),
      },
    };
  },

  created() {
    this.editedWp = Object.assign({}, this.blankPoint);
  },

  async mounted() {
    await this.loadData();
  },

  methods: {
    async filterData() {
      await this.loadData();
    },

    showAddNewForm() {
      this.$refs.wpModal.show(this.blankPoint, "Add New Data Point");
    },

    async onSaved() {
      await this.loadData();
    },

    async loadData() {
      let url = "/api/weather?latest=y";
      if (this.startDate) url += `&start=${this.startDate}`;
      if (this.endDate) url += `&end=${this.endDate}`;
      if (this.lat && this.lon) url += `&lat=${this.lat}&lon=${this.lon}`;
      const response = await axios.get(url);
      if (response.status === 200) {
        this.weatherPoints = response.data;
      } else {
        console.warn("API backend returned error");
      }
    },

    clickWP(idx) {
      this.editedWp = Object.assign({}, this.weatherPoints[idx]);
      this.$refs.wpModal.show(this.editedWp, "Edit Data Point");
    },
  },
};
</script>

<style scoped>
form.controls {
  margin-top: 2rem;
  margin-bottom: 2rem;
}
</style>
