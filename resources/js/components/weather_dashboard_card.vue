<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card">
          <div class="card-header">My Weather dashboard</div>

          <div class="card-body">
            <h1>Weather Data</h1>
            <form class="controls" novalidate>
              <h3>Filters</h3>
              <Input
                v-model="startDate"
                type="date"
                ref="startDate"
                label="Start Date"
                class="date"
                :error="errors.start"
              />
              <Input
                v-model="endDate"
                type="date"
                ref="endDate"
                label="End Date"
                class="date"
                :error="errors.end"
              />
              <Input
                v-model="lat"
                type="text"
                ref="lat"
                label="Latitude"
                class="latlon"
                :error="errors.lat"
              />
              <Input
                v-model="lon"
                type="text"
                ref="lan"
                label="Longitude"
                class="latlon"
                :error="errors.lon"
              />
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
            <div v-for="(wp, idx) in weatherPoints" :key="`wp.${idx}`">
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
      errors: {},
      fubar: null,
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
      try {
        const response = await axios.get(url);
        this.weatherPoints = response.data;
      } catch (error) {
        if (error.response.status === 422)
          this.errors = error.response.data.errors;
        else console.error("Backend error ", error.response);
      }
    },

    clickWP(idx) {
      this.editedWp = Object.assign({}, this.weatherPoints[idx]);
      this.$refs.wpModal.show(this.editedWp, "Edit Data Point");
    },
  },
};
</script>

<style lang="scss">
@import "./resources/sass/variables";

.card-header {
  background-color: black !important;
  color: white;
  font-weight: 600;
}

h1 {
  font-size: 24px;
  color: #778;
}

form.controls {
  margin-top: 2rem;
  margin-bottom: 2rem;

  h3 {
    font-size: 1.2rem;
    color: #778;
    margin-bottom: 1rem;
  }

  .date {
    width: 8rem;
  }

  .latlon {
    width: $latlon-width;
  }

  button {
    margin-left: 0.5rem;

    &:first-of-type {
      margin-left: 1rem;
    }
  }
}
</style>
