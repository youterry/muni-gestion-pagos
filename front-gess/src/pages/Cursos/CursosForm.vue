<template>
  <q-card class="my-card">
    <q-card-section class="bg-primary text-white">
      <div class="row">
        <div class="text-h6">{{ title }}</div>
        <q-space />
        <q-btn v-close-popup round size="sm" flat>
          <q-icon name="close" />
        </q-btn>
      </div>

      <!-- <div class="text-subtitle2">by John Doe</div> -->
    </q-card-section>
    <q-separator />

    <!-- <pre>{{props}}</pre> -->

    <form @submit.prevent="submit">
      <q-card-section>
        <ComponenteForm v-model="form" prop-path="curso"></ComponenteForm>
      </q-card-section>
      <q-separator />
      <q-card-actions align="right">
        <q-btn color="gray" outline label="Cancelar" v-close-popup />
        <q-btn type="submit" color="positive" outline label="Guardar" />
      </q-card-actions>
    </form>
  </q-card>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import ComponenteForm from './ComponenteCurso.vue'
import formCurso from './FormCurso'
import { useForm } from 'laravel-precognition-vue'

const emits = defineEmits(['save'])

// const model = defineModel()

const props = defineProps({
  id: {
    type: Number,
  },
  title: String,
})
const form = ref(null)
if (props.id) {
  form.value = useForm('put', 'api/cursos/' + props.id, formCurso)
} else {
  form.value = useForm('post', 'api/cursos', formCurso)
}

onMounted(() => {
  console.log('hello', props)
  // model.value = form.value
})
const submit = () => {
  console.log('submit')
  form.value
    .submit()
    .then((response) => {
      emits('save', response.data)
      form.value.reset()
      // form.setData()
    })
    .catch((error) => {
      // notifyError(error)
      // alert("An error occurred.");
    })
}

defineExpose({
  form,
})
</script>
<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 60vw
</style>
