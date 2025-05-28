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
    </q-card-section>
    <q-separator />

    <form @submit.prevent="submit">
      <q-card-section>
        <ComponentePago v-model="form" prop-path="pago"></ComponentePago>
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
import { onMounted, ref } from 'vue';
import ComponentePago from './ComponentePago.vue';
import formPago from './FormPago';
import { useForm } from 'laravel-precognition-vue';
import { useQuasar } from 'quasar';

const emits = defineEmits(['save']);
const $q = useQuasar();

const props = defineProps({
  id: {
    type: [Number, String],
    default: null
  },
  title: String,
});

const form = ref(null);
if (props.id) {
  form.value = useForm('put', `api/pagos/${props.id}`, formPago);
} else {
  form.value = useForm('post', 'api/pagos', formPago);
}

const submit = () => {
  form.value
    .submit()
    .then((response) => {
      emits('save', response.data);
      form.value.reset();
      $q.notify({
        type: 'positive',
        message: 'Pago guardado con éxito.',
        position: 'top-right',
        progress: true,
        timeout: 1000,
      });
    })
    .catch((error) => {
      console.error('Error al guardar el pago:', error);
      $q.notify({
        type: 'negative',
        message: 'Error al guardar el pago. ' + (error.response?.data?.message || 'Verifica los campos.'),
        position: 'top-right',
        progress: true,
        timeout: 2000,
      });
    });
};

defineExpose({
  form,
});
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 60vw
</style>
