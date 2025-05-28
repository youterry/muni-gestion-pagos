<template>
  <q-dialog v-model="formPagoVisible" persistent>
    <PagosForm
      :title="title"
      :id="editId"
      ref="pagoformRef"
      @save="onPagoSave"
    ></PagosForm>
  </q-dialog>

  <q-page>
    <div class="q-pa-md q-gutter-sm">
      <q-breadcrumbs>
        <q-breadcrumbs-el icon="home" label="Inicio" />
        <q-breadcrumbs-el label="Pagos" icon="mdi-credit-card" />
      </q-breadcrumbs>
    </div>
    <q-separator />

    <div class="q-gutter-xs q-pa-sm">
      <q-btn
        outline
        color="primary"
        :disable="loading"
        :label="$q.screen.lt.sm ? '' : 'Registrar Nuevo Pago'"
        icon-right="add"
        @click="openAddForm"
      />
    </div>

    <q-card class="q-ma-sm" flat :bordered="!$q.dark.isActive">
      <q-table
        flat
        :bordered="!$q.dark.isActive"
        table-header-class="my-custom"
        :rows-per-page-options="[7, 10, 15, 20]"
        class="my-sticky-header-table htable q-ma-sm"
        title="Registro de Pagos"
        ref="tableRef"
        :rows="rows"
        :columns="columns"
        row-key="id"
        v-model:pagination="pagination"
        :loading="loading"
        :filter="filter"
        binary-state-sort
        @request="onRequest"
      >
        <template v-slot:top-right>
          <q-input
            active-class="text-white"
            standout="bg-primary"
            color="white"
            dense
            debounce="500"
            v-model="filter"
            placeholder="Buscar"
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>

        <template v-slot:header="props">
          <q-tr :props="props">
            <q-th v-for="col in props.cols" :key="col.name" :props="props">
              {{ col.label }}
            </q-th>
            <q-th auto-width> Acciones </q-th>
          </q-tr>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <template v-if="col.format">
                {{ col.format(col.value, props.row) }}
              </template>
              <template v-else>
                {{ col.value }}
              </template>
            </q-td>
            <q-td auto-width>
              <q-btn
                size="sm"
                outline
                color="green"
                round
                @click="editPago(props.row.id)"
                icon="edit"
                class="q-mr-xs"
              />
              <q-btn
                size="sm"
                outline
                color="red"
                round
                @click="deletePago(props.row.id)"
                icon="delete"
              />
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PagoService from 'src/services/PagoService';
import { useQuasar, date } from 'quasar'; // Importa 'date' utility de Quasar
import PagosForm from './PagosForm.vue';

const $q = useQuasar();

const columns = [
  { name: 'id', label: 'ID Pago', align: 'left', field: 'id', sortable: true },
  { name: 'user_id', label: 'ID Usuario', align: 'left', field: 'user_id', sortable: true },
  {
    name: 'monto',
    label: 'Monto',
    align: 'right',
    field: 'monto',
    format: val => {
      // Intenta siempre convertir a número. Si es null/undefined/cadena vacía, parseFloat devuelve NaN.
      // Si la API lo envía como string, parseFloat lo maneja si tiene '.' como separador.
      const numVal = parseFloat(val);
      // Retorna el valor formateado o '0.00' si no es un número válido.
      return `$${isNaN(numVal) ? '0.00' : numVal.toFixed(2)}`;
    },
    sortable: true
  },
  {
    name: 'fecha',
    label: 'Fecha',
    align: 'center',
    field: 'fecha',
    sortable: true,
    format: val => {
      // Usa el date utility de Quasar para un parseo y formato más robusto.
      // Si val es null o undefined, date.isValid retorna false.
      if (!val || !date.isValid(val)) {
        return 'Fecha inválida';
      }
      // Formatea la fecha a DD/MM/YYYY
      return date.formatDate(val, 'DD/MM/YYYY');
    }
  },
  { name: 'estado', label: 'Estado', align: 'center', field: 'estado', sortable: true },
];

const tableRef = ref();
const formPagoVisible = ref(false);
const pagoformRef = ref();

const title = ref('');
const editId = ref(null);

const rows = ref([]);
const filter = ref('');
const loading = ref(false);

const pagination = ref({
  sortBy: 'id',
  descending: false,
  page: 1,
  rowsPerPage: 9,
  rowsNumber: 10,
});

async function onRequest(props) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination;
  const filter = props.filter;
  loading.value = true;

  const fetchCount = rowsPerPage === 0 ? 0 : rowsPerPage;
  const order_by = descending ? '-' + sortBy : sortBy;

  try {
    const { data, total = 0 } = await PagoService.getData({
      params: { rowsPerPage: fetchCount, page, search: filter, order_by },
    });

    // Filtra filas que puedan tener 'id' undefined si la API lo devuelve mal
    rows.value.splice(0, rows.value.length, ...data.filter(row => row.id !== undefined));

    pagination.value.rowsNumber = total;
    pagination.value.page = page;
    pagination.value.rowsPerPage = rowsPerPage;
    pagination.value.sortBy = sortBy;
    pagination.value.descending = descending;

  } catch (error) {
    console.error('Error al cargar los pagos:', error);
    $q.notify({
      type: 'negative',
      message: 'Error al cargar los pagos. ' + (error.response?.data?.message || 'Intenta de nuevo más tarde.'),
      position: 'top-right',
      progress: true,
      timeout: 2000,
    });
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  tableRef.value.requestServerInteraction();
});

const openAddForm = () => {
  title.value = 'Registrar Nuevo Pago';
  editId.value = null; // No hay ID para un nuevo pago
  formPagoVisible.value = true;
  if (pagoformRef.value && pagoformRef.value.form) {
    pagoformRef.value.form.reset();
  }
};

const onPagoSave = () => {
  formPagoVisible.value = false;
  tableRef.value.requestServerInteraction(); // Recarga la tabla
};

async function editPago(id) {
  title.value = 'Editar Pago';
  editId.value = id;
  formPagoVisible.value = true;

  try {
    const row = await PagoService.get(id);
    // Pre-formatea la fecha para el q-input type="date" (YYYY-MM-DD)
    // Usamos date.formatDate para asegurar el formato correcto a partir de la cadena ISO
    const formattedRow = {
      ...row,
      fecha: row.fecha ? date.formatDate(row.fecha, 'YYYY-MM-DD') : null,
    };
    pagoformRef.value.form.setData({ pago: formattedRow });
  } catch (error) {
    console.error('Error al cargar el pago para edición:', error);
    $q.notify({
      type: 'negative',
      message: 'Error al cargar los datos del pago para edición.',
      position: 'top-right',
      progress: true,
      timeout: 2000,
    });
    formPagoVisible.value = false;
  }
}

async function deletePago(id) {
  $q.dialog({
    title: 'Confirmar Eliminación',
    message: '¿Estás seguro de que quieres eliminar este pago? Esta acción no se puede deshacer.',
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      await PagoService.delete(id);
      tableRef.value.requestServerInteraction(); // Recarga la tabla
      $q.notify({
        type: 'positive',
        message: 'Pago eliminado con éxito.',
        position: 'top-right',
        progress: true,
        timeout: 1000,
      });
    } catch (error) {
      console.error('Error al eliminar pago:', error);
      $q.notify({
        type: 'negative',
        message: 'Error al eliminar el pago. ' + (error.response?.data?.message || 'Intenta de nuevo más tarde.'),
        position: 'top-right',
        progress: true,
        timeout: 2000,
      });
    }
  });
}
</script>

<style lang="sass" scoped>
.my-custom
  background-color: $primary
  color: white

.htable
  min-height: 400px

.q-table__top,
.q-table__bottom,
thead tr:first-child th
  background-color: white

.q-table--dark .q-table__top,
.q-table--dark .q-table__bottom,
.q-table--dark thead tr:first-child th
  background-color: #1d1d1d

.q-table--loading thead tr:last-child th
  top: 48px

thead tr:first-child th
  top: 0
  z-index: 1

thead tr:last-child th
  top: 48px
  z-index: 1
</style>
