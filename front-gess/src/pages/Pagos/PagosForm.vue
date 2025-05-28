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
import { ref } from 'vue';
import ComponentePago from './ComponentePago.vue'; // Asegúrate de que la ruta es correcta si lo moviste
import formPago from './FormPago'; // Asegúrate de que la ruta es correcta
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
  // Si hay un ID, es una edición (PUT)
  form.value = useForm('put', `api/pagos/${props.id}`, formPago);
} else {
  // Si no hay ID, es una creación (POST)
  form.value = useForm('post', 'api/pagos', formPago);
}

const submit = () => {
  form.value
    .submit()
    .then((response) => {
      emits('save', response.data);
      // form.value.reset(); // El reset lo manejamos en PagosList para consistencia
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
      let errorMessage = 'Error al guardar el pago. Verifica los campos.';
      // Intenta extraer el mensaje de error de la validación de Laravel si está disponible
      if (error.response && error.response.data && error.response.data.errors) {
        const errors = error.response.data.errors;
        // Concatenar todos los mensajes de error de validación
        errorMessage += ' Detalles: ';
        for (const field in errors) {
          errorMessage += `${errors[field].join(', ')} `;
        }
      } else if (error.response?.data?.message) {
        errorMessage += ' ' + error.response.data.message;
      }
      $q.notify({
        type: 'negative',
        message: errorMessage,
        position: 'top-right',
        progress: true,
        timeout: 5000, // Aumenta el tiempo para leer errores de validación
      });
    });
};

defineExpose({
  form, // Exponer el formulario para que el componente padre (PagosList) pueda resetearlo o setear datos
});
</script>

<style lang="sass" scoped>
.my-card
  width: 100%
  max-width: 60vw
</style>
