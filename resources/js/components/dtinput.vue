<template>
<div class="dtinput">
    <input type="text" v-model="value" readonly="1">
    <table>
        <tr v-for="(week, widx) in days" :key="`week${widx}`">
            <td v-for="(day, didx) in week"
                :class="{selected: widx === weekIdx && didx === dayIdx, inactive: day.month < days[2][4].month}"
                :key="`day_${widx}_${didx}`">
              {{ day.day }}
            </td>
        </tr>
    </table>
</div>
</template>

<script>
import { DateTime } from 'luxon'

const DayCodes = ''

export default {
    name: 'dtinput',

    props: {
        value: {
            type: String,
            required: true,
            validator(value) {
                return DateTime.fromISO(value).isValid
            }
        }
    },

    data() {
        return {
            days: null,
            // Selected day position in this.days
            weekIdx: null,
            dayIdx: null
        }
    },

    created() {
        this.today = DateTime.fromD
        const today = DateTime.fromISO(this.value)
        this.generateMonth(today, true)
    },

    mounted() {
        window.dttm = DateTime.fromJSDate(new Date())
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
        }
    }
}
</script>

<style lang="scss" scoped>
    .dtinput {
        margin-bottom: 2rem;
        table {
            border-collapse: separate;
            background-color: white;
            border-spacing: 0 8px;
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
