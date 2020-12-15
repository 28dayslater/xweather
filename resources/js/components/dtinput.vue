<template>
<div class="date-picker">
    <input type="text" v-model="value" readonly="1">
    <table v-show="isVisible">
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
</div>
</template>

<script>
import { DateTime } from 'luxon'

export default {
    name: 'DatePicker',

    props: {
        value: {
            type: String,
            required: true,
            validator(value) {
                return value.trim() === '' || DateTime.fromISO(value).isValid
            }
        }
    },

    data() {
        return {
            isVisible = false,
            days: null,
            // Selected day position in this.days
            weekIdx: null,
            dayIdx: null
        }
    },

    computed: {
        currentYear() {
            return this.days[2][2].year
        },

        currentMonth() {
            return this.days[2][2].monthShort
        }
    },

    created() {
        let current = DateTime.fromJSDate(new Date())
        if (this.value !== '') {
            current = DateTime.fromISO(this.value)
            if (current.toISODate() !== this.value)
                this.$emit('input', current.toISODate())
        }
        this.generateMonth(current, true)
    },

    methods: {
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

        show() {

        },

        chooseDay(widx, didx) {
            if (widx >= 0 && widx < this.days.length
                && didx >= 0 && didx < this.days[widx].length) {

                const day = this.days[widx][didx]
                if (day.month === this.days[2][3].month) // Ignore the filler days
                    this.$emit('input', day.toISODate())
            }

        },

        shift(spec) {
            const seed = this.days[2][2]
            switch (spec) {
                case '-y':
                    return this.generateMonth(seed.minus({years:1}))
                case '+y':
                    return this.generateMonth(seed.plus({years:1}))
                case '-m':
                    return this.generateMonth(seed.minus({months:1}))
                case '+m':
                    return this.generateMonth(seed.plus({months:1}))
            }
            // TODO: move the selected day to the nearest day in the current month
        }
    }
}
</script>

<style lang="scss" scoped>
    .dtinput {
        margin-bottom: 2rem;
        user-select: none;

        table {
            border-collapse: separate;
            background-color: white;
            border-spacing: 0 8px;
        }

        thead {
            th > div {
                display: flex;
                justify-content: space-between;
            }
            div {
                color: #555;
                display: inline-block;
            }
            svg {
                display: inline-block;
                cursor: pointer;

                &:hover {
                    color: #7b7be0;
                }
            }
            span {
                vertical-align: top;
                line-height: 2rem;
                font-size: 12px;
                font-weight: normal;
                margin-left: -7px;
                margin-right: -7px;
            }
        }

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
