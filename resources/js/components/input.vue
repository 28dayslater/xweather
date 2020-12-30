<template>
  <div class="input" :class="{ error: error }">
    <input
      :type="inputType"
      :id="inputId"
      v-bind:value="value"
      @input="$emit('input', $event.target.value)"
      @keyup.enter="$emit('enter')"
      @focus="showCal = true"
      @blur="showCal = false"
      ref="input"
      :class="{date: isDate}"
      :readonly="isDate"
      required
    />
    <label v-if="label" :for="inputId">{{ label }}</label>
    <p v-if="error">{{ error }}</p>

    <!-- IDEA: How about making the popup calendar a separate component that is somehow attached to an input?
               Use a scoped/named slot here? --->
    <table v-if="isDate && showCal">
      <thead>
        <tr>
          <th colspan="7">
            <div>
              <div>
                <svg width="2rem" height="2rem" viewBox="0 0 24 24" @click="shift('-y')">
                  <path d="M14 7l-5 5 5 5V7z" fill="currentColor" />
                </svg>
                <span>{{ currentYear }}</span>
                <svg width="2rem" height="2rem" viewBox="0 0 24 24" style="transform: scale(-1,1)" @click="shift('+y')">
                  <path d="M14 7l-5 5 5 5V7z" fill="currentColor" />
                </svg>
              </div>
              <div>
                <svg width="2rem" height="2rem" viewBox="0 0 24 24" @click="shift('-m')">
                  <path d="M14 7l-5 5 5 5V7z" fill="currentColor" />
                </svg>
                <span>{{ currentMonth }}</span>
                <svg width="2rem" height="2rem" viewBox="0 0 24 24" style="transform: scale(-1,1)" @click="shift('+m')">
                  <path d="M14 7l-5 5 5 5V7z" fill="currentColor" />
                </svg>
              </div>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
      <tr v-for="(week, widx) in days" :key="`week${widx}`">
          <td v-for="(day, didx) in week"
              :class="{selected: widx === weekIdx && didx === dayIdx, inactive: day.month !== days[2][4].month}"
              :key="`day_${widx}_${didx}`"
              @click="chooseDay(widx,didx)">
          {{ day.day }}
          </td>
      </tr>
      </tbody>
    </table>

    <svg class="cal-ico" v-if="isDate" viewBox="0 0 24 24" width="1rem" height="1rem" pointer-events="all">
      <path d="M0 0h24v24H0V0z" fill="none"/>
      <path fill="currentColor" d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V10h16v11zm0-13H4V5h16v3z"/>
    </svg>

    <slot></slot> <!-- icons -->
  </div>
</template>

<script>
import shortuuid from "short-uuid";
import { DateTime } from 'luxon'

export default {
  name: "Input",

  props: {
    value: {
      type: String,
      required: true,
    },
    type: String,   // TODO: validate if text|date|password
    label: String,
    error: String,
  },

  data() {
    let ret = {
      inputId: "",
      inputType: "text",
    }
    if (this.type === 'date')
      ret = {...ret, ...{
        days: null,
        weekIdx: null,
        dayIdx: null,
        showCal: false
      }}
    return ret
  },

  computed: {
    isDate() { return this.type === 'date' },
  },

  created() {
    this.inputId = shortuuid.generate();
    if (this.type === 'date') {
      const seedDay = DateTime.fromISO(this.value)
      this.generateMonth(seedDay, true)
    }
  },

  methods: {
    focus() {
      this.$refs.input.focus();
    },

    generateMonth(day, markDay = false) {
        const start = day.startOf('month').startOf('week')
        const end = day.endOf('month').endOf('week')
        this.days = []
        let week = []
        for (let dt = start; dt <= end; dt = dt.plus({days:1})) {
            week.push(dt)
            if (markDay && dt.equals(day)) {
                this.weekIdx = this.days.length
                this.dayIdx = dt.weekday - 1
            }
            if (dt.weekday === 7) {
                this.days.push(week)
                week = []
            }
        }
    },
  },
};
</script>

<style lang="scss">
div.input {
  display: inline-block;
  position: relative;
  padding: 3px 5px;

  &.error input {
    border-color: red;
    color: red;
  }

  &.error label + p {
    color: red;
    position: absolute;
    font-size: 0.8rem;
    bottom: -1rem;
    margin: 0;
  }
}

input {
  width: 100%;
  padding: 3px 0;
  outline: none;
  border: none;
  background-color: inherit;
  border-bottom: solid 1px #777;

  &.date {
    padding-right: 1.5rem;
  }
}

input:focus {
  border-bottom: solid 1px #4299e1;
}

label {
  position: absolute;
  cursor: text;
  top: 6px;
  left: 5px;
  color: #999;
  font-size: 1rem;
  transition: 0.2s;
}

input:focus + label,
input:valid + label {
  cursor: default;
  margin-top: -18px;
  left: 5px;
  font-size: 0.8rem;
}

svg.cal-ico {
  position: absolute;
  right: -0.2rem;
  top: 0.7rem;
}

table.cal {
  border-collapse: separate;
  background-color: white;
  border-spacing: 0 8px;

  td {
    padding: 1px;
    color: #7b7be0;
    text-align: center;
    vertical-align: middle;
    border: none;
    width: 2rem;
    height: 2rem;
    font-size: 80%;
    cursor: pointer;

    &.inactive {
        color: #aaa;
    }

    &:hover:not(.inactive) {
        padding: 0;
        margin-top: -1px;
        background-color: #f0f0f0;
        border-radius: 50%;
        color: #7b7be0;
    }

    &.selected {
        background-color: #7b7be0;
        color: #fff;
        border-radius: 50%;
        font-weight: bold;
    }
  }
}

</style>
