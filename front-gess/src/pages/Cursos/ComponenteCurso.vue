<template>
  <!--
    <p>Componente model{{ model }}</p>
  <p>prps path {{ propPath }}</p>
  <p>xdxd {{ model['trabajador.persona'] }}</p>
  <p>truee {{ model.invalid(props.propPath + '.numero_documento') }}</p>
  <p>error {{ valor.errors }}</p>
  <p>error or {{ error }}</p>
  <p>error {{ model.errors }}</p>
  -->
  <!--
  <p>
    valorr
    {{ dataGet(model, propPath + '.numero_documento', 'no existe') }}
  </p> -->
  <!-- <p>oficina id: {{ valor }}</p> -->
  <div class="row q-col-gutter-sm">
    <div class="col-12 col-md-6">
      <q-input
        dense
        outlined
        v-model="valor.nombre"
        :loading="model.validating"
        label="Nombre"
        @change="model.validate(propPath + '.nombre')"
        :error="model.invalid(propPath + '.nombre')"
        :class="model.invalid(propPath + '.nombre') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-key" /></template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.nombre'] }}</div>
        </template>
      </q-input>
    </div>
    <div class="col-12 col-md-6">
      <q-input
        dense
        outlined
        v-model="valor.descripcion"
        :loading="model.validating"
        label="Descripcion"
        @change="model.validate(propPath + '.descripcion')"
        :error="model.invalid(propPath + '.descripcion')"
        :class="model.invalid(propPath + '.descripcion') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-key" /> </template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.descripcion'] }}</div>
        </template>
      </q-input>
    </div>


  </div>
</template>

<script setup>
import { computed, onMounted, readonly, ref } from 'vue'

// const oficina = defineModel('oficina')
// const oficina_id = defineModel('oficina_id')
const oficina_id = defineModel('oficina_id')
const xd = ref(1)
const props = defineProps({
  propPath: String,
  readonly: {
    type: Boolean,
    default: false,
  },
})
const model = defineModel()
const loading = ref(false)

// const personaBind = computed({
//   get() {
//     console.log('get')
//     return getNestedValue(model, props.propPath)
//   },
//   set(newVal) {
//     console.log('set')
//     setNestedValue(model.value, props.propPath, newVal)
//   },
// })

// function getNestedValue(obj, path) {
//   return path.split('.').reduce((acc, key) => acc?.[key], obj)
// }

// function setNestedValue(obj, path, value) {
//   const keys = path.split('.')
//   const lastKey = keys.pop()
//   const parent = keys.reduce((acc, key) => acc[key], obj)
//   parent[lastKey] = value
// }

onMounted(() => {
  console.log('helloxd', dataGet(model, props.propPath + '.oficina_id', 'no existe'))

  // console.log(props.propPath);
})
const valor = computed(() => {
  // return props.propPath.split('.').forEach((o, i) => console.log('indice', o))
  return props.propPath.split('.').reduce((o, i) => (o ? o[i] : undefined), model.value)
})

// const error = computed(() => {
//   // return props.propPath.split('.').forEach((o, i) => console.log('indice', o))
//   return props.propPath.split('.').reduce((o, i) => (o ? o[i] : undefined), model.value.errors)
// })

function deepGet(obj, path) {
  return path.split('.').reduce((o, i) => (o ? o[i] : undefined), obj)
}

const error = computed(() => deepGet(model.value?.errors, props.propPath))

function dataGet(obj, path, defaultValue = null) {
  return (
    path.split('.').reduce((acc, key) => {
      return acc && acc[key] !== undefined ? acc[key] : undefined
    }, obj) ?? defaultValue
  )
}
</script>
<style lang="sass" scoped></style>
