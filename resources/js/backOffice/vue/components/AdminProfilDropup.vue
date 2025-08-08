<template>
    <div class="relative" v-click-outside="closeDropup">
        <!-- Bouton du profil -->
        <div
            class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50 transition-colors duration-150"
            @click="toggleDropup"
        >
            <slot name="buttonProfil"></slot>
        </div>

        <!-- Dropup Menu -->
        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="isOpen"
                class="absolute bottom-full left-0 mb-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
            >
                <slot name="menuDropup"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    name: 'AdminProfilDropup',
    data() {
        return {
            isOpen: false
        }
    },

    methods: {
        toggleDropup() {
            this.isOpen = !this.isOpen
        },

        closeDropup() {
            this.isOpen = false
        }
    },

    directives: {
        'click-outside': {
            beforeMount(el, binding) {
                el.clickOutsideEvent = function(event) {
                    // Vérifier si le clic est en dehors de l'élément
                    if (!(el === event.target || el.contains(event.target))) {
                        binding.value()
                    }
                }
                document.addEventListener('click', el.clickOutsideEvent)
            },
            unmounted(el) {
                document.removeEventListener('click', el.clickOutsideEvent)
            }
        }
    }
}
</script>

<style scoped>
/* Styles supplémentaires si nécessaire */
</style>
