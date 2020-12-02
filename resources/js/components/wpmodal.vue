<template>
  <div class="content" v-show="showModal" ref="modal">
    <h2>{{ message }}</h2>
    <div id="wpform">
      <div class="error" v-if="temperatureCountError">{{ temperatureCountError }}</div>
      <Input
        type="text"
        v-model="point.date"
        label="Date"
        ref="date"
        :error="errors.date"
      />
      <Input
        type="text"
        v-model="point.location.city"
        label="City"
        ref="city"
        :error="errors['location.city']"
      />
      <Input
        type="text"
        v-model="point.location.state"
        label="State"
        ref="state"
        class="state"
        :error="errors['location.state']"
      />
      <div class="latlon">
        <Input
          type="text"
          v-model="point.location.lat"
          label="Latitude"
          ref="lat"
          class="geo"
          :error="errors['location.lat']"
        />
        <Input
          type="text"
          v-model="point.location.lon"
          label="Longitude"
          ref="lon"
          class="geo"
          :error="errors['location.lon']"
        />
      </div>
      <div class="temps">
        <h6>Hourly temperatures</h6>
        <Input
          type="text"
          v-for="(temp, idx) in point.temperature"
          :key="idx"
          v-model="point.temperature[idx]"
          :label="hour(idx)"
          :error="tempError(idx)"
        />
      </div>

      <div class="buttons">
        <button class="btn btn-sm btn-primary" @click="save">Save</button>
        <button class="btn btn-sm btn-secondary" @click="cancel">Cancel</button>
      </div>
    </div>
    <div
      class="backdrop"
      v-show="showModal"
      ref="backdrop"
      @click="cancel"
    ></div>
  </div>
</template>

<script>
import Input from "./input.vue";
import { cloneDeep } from "lodash";

export default {
  components: {
    Input,
  },

  data() {
    return {
      message: "",
      showModal: false,
      point: {
        id: null,
        date: "",
        location: {
          city: "",
          state: "",
          lat: "",
          lon: "",
        },
        temperature: Array(24).fill(""),
      },
      errors: {},
    };
  },

  computed: {
    temperatureCountError() {
      return this.errors['temperature']
    },
  },

  mounted() {
    document.body.prepend(this.$refs.backdrop);
  },

  methods: {
    show(point, message) {
      this.point = cloneDeep(point);
      this.message = message;
      this.showModal = true;
    },

    hour(idx) {
      return idx < 12
        ? `${idx % 12} am`
        : idx == 12
        ? `${idx} pm`
        : `${idx % 12} pm`;
    },

    tempError(idx) {
      return this.errors[`temperature.${idx}`]
    },

    async save(event) {
      event.preventDefault();
      try {
        this.errors = {};
        let id = this.point.id;
        let resp = null
        if (id)
          resp = await axios.patch(`/api/weather`, this.point)
        else
          resp = await axios.post(`/api/weather`, this.point)
        if (resp.status === 200 || resp.status === 201) {
          this.showModal = false;
          this.$emit("saved");
        }
      } catch (error) {
        const errors = {};
        for (const err of Object.entries(error.response.data.errors))
          // Laravel validator puts the error messages in an array
          errors[err[0]] = err[1][0]
          this.errors = errors
      }
    },

    cancel(event) {
      event.preventDefault();
      this.showModal = false;
    },
  },
};
</script>

<style lang="scss">
@import "./resources/sass/variables";

.backdrop {
  position: fixed;
  left: 0;
  top: 0;
  min-width: 100%;
  min-height: 100%;
  z-index: 1;
  background-color: black;
  opacity: 0.5;
}

.content {
  position: relative;
  z-index: 500;
  background-color: #f0f0f0;
  opacity: 1;
  width: 45rem;
  max-width: 90%;
  margin: 10px auto;
  padding: 2rem;
  border-radius: 5px;
  box-shadow: 5px 5px 10px #333;

  .input {
    margin-bottom: 1.2rem;
  }

  .latlon {
    margin-top: 1rem;
    width: auto !important;
  }

  .temps {
    margin-top: 1rem;
  }

  .state {
    width: 4rem;
  }

  .geo {
    width: $latlon-width;
  }

  .temps .input {
    width: 4rem;
  }

  ul.errors {
    list-style: none;
    color: red;
  }
}
</style>
