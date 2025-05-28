<template>
  <q-layout view="lHh lpR fFf">
    <q-header
      :style="
        $q.dark.isActive ? 'background: #1d1d1d' : 'background: #fafafa; color: rgb(0, 0, 0);'
      "
    >
      <q-toolbar>
        <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
        <q-toolbar-title> </q-toolbar-title>
        </q-toolbar>
    </q-header>

    <q-drawer
      show-if-above
      v-model="drawer"
      side="left"
      :style="
        $q.dark.isActive
          ? 'background: $dark'
          : 'background: #004173; color: rgb(0, 0, 0); color: white'
      "
    >
      <q-scroll-area style="height: calc(100% - 165px); margin-top: 165px" class="q-mx-sm">
        <q-list padding>
          <q-item
            :to="{ name: 'Dashboard' }"
            :active="link === 'Dashboard'"
            @click="link = 'Dashboard'"
            clickable
            v-ripple
            dense
            class="q-ma-xs rounded-borders q-pa-sm"
            active-class="my-menu-link"
          >
            <q-item-section side>
              <LayoutDashboard stroke-width="1.8" size="25px" color="white" />
            </q-item-section>

            <q-item-section>
              <span :class="link === 'Dashboard' ? 'text-weight-bold' : 'text-weight-medium'"
                >Dashboard</span
              >
            </q-item-section>
          </q-item>
          <q-item :to="{ name: 'Curso' }" :active="link === 'Curso'" @click="link = 'Curso'" clickable v-ripple dense class="q-ma-xs rounded-borders q-pa-sm" active-class="my-menu-link" >
            <q-item-section side>
              <SquareLibrary stroke-width="1.8" size="25px" color="white" />
            </q-item-section>

            <q-item-section>
              <span :class="link === 'Curso' ? 'text-weight-bold' : 'text-weight-medium'"
                >Curso</span
              >
            </q-item-section>
          </q-item>

          <q-item
            :to="{ name: 'Pagos' }"
            :active="link === 'Pagos'"
            @click="link = 'Pagos'"
            clickable
            v-ripple
            dense
            class="q-ma-xs rounded-borders q-pa-sm"
            active-class="my-menu-link"
          >
            <q-item-section side>
              <q-icon name="mdi-credit-card" size="25px" color="white" />
            </q-item-section>
            <q-item-section>
              <span :class="link === 'Pagos' ? 'text-weight-bold' : 'text-weight-medium'"
                >Pagos</span
              >
            </q-item-section>
          </q-item>
          </q-list>
      </q-scroll-area>
      <div class="absolute-bottom q-pa-xs">
        <q-separator spaced dark />
        <q-item
          @click="logout()"
          clickable
          v-ripple
          class="bg-red-10 text-white q-ma-sm"
          style="margin: 6px; border-radius: 10px"
        >
          <q-item-section side>
            <q-icon name="ion-ios-power" color="white" />
          </q-item-section>
          <q-item-section> CERRAR SESIÓN </q-item-section>
        </q-item>
      </div>

      <div class="absolute-top q-pa-none" style="height: 165px">
        <q-item
          clickable
          v-ripple
          :to="{ name: 'Dashboard' }"
          :active="link === 'Dashboard'"
          @click="link = 'Dashboard'"
          class="text-white q-ma-sm"
          style="margin: 6px; margin-left: 16px; margin-right: 16px; border-radius: 10px"
        >
          <q-item-section avatar>
            <q-avatar rounded size="48px">
              <img
                src="https://pbs.twimg.com/profile_images/1092871680781967360/Bj6cjBrC_400x400.jpg"
              />
            </q-avatar>
          </q-item-section>
          <q-item-section>
            <span class="text-weight-bold" style="font-size: 18px">
              SISTEMA MUNI SIAGC
            </span>
          </q-item-section>
        </q-item>
        <q-item
          @mouseover="hover = true"
          @mouseleave="hover = false"
          clickable
          v-ripple
          :to="{ name: 'Perfil' }"
          :active="link === 'Perfil'"
          @click="link = 'Perfil'"
          class="text-white q-ma-sm"
          style="
            margin: 6px;
            margin-bottom: 16px;
            margin-left: 16px;
            margin-right: 16px;
            border-radius: 10px;
            border: 1px solid #ffffff50;
          "
        >
          <q-item-section>
            <q-item-label lines="1">
              <span class="text-weight-bold"> JORN VAN DEYNHOVEN </span>
            </q-item-label>
            <q-item-label caption lines="2" class="text-white">
              <span class="text-weight-bold">Admin</span>
              - <span> OTI </span>
            </q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-avatar color="secondary" text-color="white">
              <q-icon v-if="hover" name="ion-settings" />
              <span v-else>D</span>
            </q-avatar>
          </q-item-section>
        </q-item>

        <q-separator spaced dark />
      </div>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { LayoutDashboard } from 'lucide-vue-next'
import { User } from 'lucide-vue-next'
import { SquareLibrary } from 'lucide-vue-next';
const hover = ref(false)
// REMOVED: import { Megaphone } from 'lucide-vue-next'; (No se usa en el template, causa warning si no está instalada)

import { computed, onMounted, ref } from 'vue'
// import SwitchDarkMode from 'components/SwitchDarkMode.vue'
import { useRoute } from 'vue-router'
// import { useUserStore } from 'src/stores/user-store'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { Settings } from 'lucide-vue-next' // Esta sí se usa (para el icono de Perfil)

const $q = useQuasar()
// const userStore = useUserStore()
const drawer = ref(false)
const miniState = ref(false)
const route = useRoute()
const link = ref(route.name)
const router = useRouter()
// onMounted(async () => {
//   await userStore.getUser()
// })

// const logout = async () => {
//   userStore.logout()
//   router.push({ name: 'Login' })
// }
// const initialsMayus = computed(() => {
//   // Divide la frase por espacios en palabras
//   const words = userStore.getAlias?.split(' ')

//   // Obtiene la primera letra de cada palabra y la convierte a mayúscula
//   const initials = words?.map((word) => word.charAt(0).toUpperCase())

//   // Une las iniciales para formar la frase final

//   return initials?.join('')
//   // console.log(userStore.getName);
//   // return name.value;
// })
</script>

<style lang="scss">
.my-menu-link {
  color: white;
  background: #0088cc !important;
  // border-left: 4px solid white;
  border-radius: 10px !important;
}

.drawer-header {
  display: flex;
  justify-content: center;
  align-items: center;
}

.drawer-avatar-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.q-scrollarea__thumb--v {
  width: 6px;
}

.q-dialog__backdrop {
  background-color: rgba(0, 0, 0, 0.8);
}

.q-field__control {
  border-radius: 10px !important;
}

.q-btn,
.q-card {
  border-radius: 10px !important;
}

body.body--light .q-page {
  background-color: #f0f0f0 !important;
}

.q-table th:first-child {
  border-top-left-radius: 10px !important;
}

.q-table th:last-child {
  border-top-right-radius: 10px !important;
}

.drawer-footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 6px 0;
}
.floating-avatar {
  position: absolute;
  z-index: 1;
  margin-left: 130px;
  margin-bottom: -30px;
}

.hoverable-card {
  transition: all 0.3s ease;
}

.hoverable-card:hover {
  border-color: #ffffff80;
  cursor: pointer;
}
</style>
