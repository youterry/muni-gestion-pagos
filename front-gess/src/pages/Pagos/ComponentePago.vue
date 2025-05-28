<template>
  <div class="row q-col-gutter-sm">
    <div class="col-12 col-md-6">
      <q-input
        dense
        outlined
        v-model="valor.monto"
        :loading="model.validating"
        label="Monto del Pago"
        type="number"
        step="0.01"
        @change="model.validate(propPath + '.monto')"
        :error="model.invalid(propPath + '.monto')"
        :class="model.invalid(propPath + '.monto') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-currency-usd" /></template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.monto'] }}</div>
        </template>
      </q-input>
    </div>

    <div class="col-12 col-md-6">
      <q-input
        dense
        outlined
        v-model="valor.fecha"
        :loading="model.validating"
        label="Fecha del Pago"
        type="date"
        @change="model.validate(propPath + '.fecha')"
        :error="model.invalid(propPath + '.fecha')"
        :class="model.invalid(propPath + '.fecha') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-calendar" /> </template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.fecha'] }}</div>
        </template>
      </q-input>
    </div>

    <div class="col-12 col-md-6">
      <q-select
        dense
        outlined
        v-model="valor.estado"
        :options="['pendiente', 'pagado', 'anulado']"
        :loading="model.validating"
        label="Estado del Pago"
        @update:model-value="model.validate(propPath + '.estado')"
        :error="model.invalid(propPath + '.estado')"
        :class="model.invalid(propPath + '.estado') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-cash-check" /> </template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.estado'] }}</div>
        </template>
      </q-select>
    </div>

    <div class="col-12 col-md-6">
      <q-input
        dense
        outlined
        v-model="valor.ID_Usuario"
        :loading="model.validating"
        label="ID de Usuario"
        type="number" @change="model.validate(propPath + '.ID_Usuario')"
        :error="model.invalid(propPath + '.ID_Usuario')"
        :class="model.invalid(propPath + '.ID_Usuario') ? '' : 'q-pb-none'"
      >
        <template v-slot:prepend> <q-icon name="mdi-account" /></template>
        <template v-slot:error>
          <div>{{ model.errors[propPath + '.ID_Usuario'] }}</div>
        </template>
      </q-input>
    </div>

  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  propPath: String,
  readonly: {
    type: Boolean,
    default: false,
  },
});
const model = defineModel();

const valor = computed(() => {
  return props.propPath.split('.').reduce((o, i) => (o ? o[i] : undefined), model.value);
});
</script>

<style lang="sass" scoped></style>
