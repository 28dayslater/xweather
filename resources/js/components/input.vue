<template>
  <div class="input" :class="{error: error}">
    <input
      :type="inputType"
      :id="inputId"
      v-bind:value="value"
      @input="$emit('input', $event.target.value)"
      @keyup.enter="$emit('enter')"
      ref="input"
      required
    />
    <label v-if="label" :for="inputId">{{ label }}</label>
    <p>{{ error }}</p>
    <slot></slot>
  </div>
</template>

<script>
import shortuuid from 'short-uuid'

export default {
  name: 'Input',

  props: {
    value: {
      type: String,
      required: true,
    },
    type: String,
    label: String,
    error: String,
  },

  data() {
    return {
      inputId: '',
      inputType: this.type,
    }
  },

  created() {
      this.inputId = shortuuid.generate()
  },

  methods: {
    focus() {
      this.$refs.input.focus()
    },
  },
}
</script>

<style lang="scss" >
    div.input {
        display: inline-block;
        position: relative;
        padding: 3px 5px;

        &.error input {
            border-color: red;
        }

        &.error label + p {
            color: red;
        }
    }

    input {
        padding: 3px 0;
        outline: none;
        border: none;
        background-color: inherit;
        border-bottom: solid 1px #777;
    }

    input:focus {
        border-bottom: solid 1px #4299e1;
    }

  p {
    font-size: 0.75rem;
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

</style>
