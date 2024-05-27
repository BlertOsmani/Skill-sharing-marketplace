<template>
    <div class="border-100 border-solid border-1 p-2 profile-dropdown-container surface-card" :class="class">
        <ul v-for="(column, i) in dropdownItems" :key="i" class="list-none flex flex-column p-0 m-1" :style="{ width: column.width }">
            <div v-for="(group, index) in column.groups" :key="index" class="mb-2">
                <p class="text-xs text-300 font-medium m-1">{{ group.title }}</p>
                <li v-for="(item, idx) in group.items" :key="idx">
                    <Link 
                        :to="item.to" 
                        :class="['w-12 text-left border-round-md', item.class]" 
                        severity="secondary" 
                        :label="item.label" 
                        :icon="item.icon" 
                        :badge="item.badge" 
                        :badgeSeverity="item.badgeSeverity"
                        :clickHandler="item.clickHandler"
                    />
                </li>
            </div>
        </ul>
    </div>
</template>

<script>
import Link from './Link.vue';
import AuthServices from '@/services/AuthServices';

export default {
    components: {
        Link,
    },
    props: {
        class: {
            type: String,
            required: true,
        },
        badge: {
            type: String,
            required: false,
        },
        dropdownItems: {
            type: Array,
            required: true,
        },
        badgeSeverity: {
            type: String,
            required: false,
        }
    },
};
</script>

<style lang="css">
.profile-dropdown-container {
    border-radius: 8px;
    position: absolute;
    margin-top: 5px;
    z-index: 100;
}
.p-badge {
    font-size: 12px;
}
</style>
