import { defineStore } from 'pinia'

export const useMenu = defineStore('MenuId', {
    state: () => ({
        selectedKeys: [],
        openKeys: [],
    }),

    actions: {
        onselectedkey(data) {
            this.selectedKeys = data;
        }, 

        onOpenKeys(data) {
            this.openKeys = data;
        }
    }
})