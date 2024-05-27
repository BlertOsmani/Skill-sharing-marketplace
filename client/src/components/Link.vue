<template>
    <div v-if="to">
        <router-link :to="to" exact>
            <Button 
                :label="label" 
                :icon="icon" 
                text 
                :severity="severity" 
                :class="computeClass()" 
                :badge="badge" 
                :badgeSeverity="badgeSeverity" 
                :iconPos="iconPos"
            >
                <slot></slot>
            </Button>
        </router-link>
    </div>
    <div v-else>
        <Button 
            :label="label" 
            :icon="icon" 
            text 
            :severity="severity" 
            :class="computeClass()" 
            :badge="badge" 
            :badgeSeverity="badgeSeverity" 
            :iconPos="iconPos" 
            @click="handleClick"
        >
            <slot></slot>
        </Button>
    </div>
</template>

<script>
import Button from 'primevue/button';

export default {
    name: 'Link',
    components: {
        Button
    },
    props: {
        to: {
            type: String,
            required: false
        },
        label: {
            type: String,
            required: false
        },
        icon: {
            type: String,
            required: false
        },
        class: {
            type: String,
            required: false
        },
        badge: {
            type: String,
            required: false
        },
        iconPos: {
            type: String,
            required: false
        },
        badgeSeverity: {
            type: String,
            required: false
        },
        severity: {
            type: String,
            required: false
        },
        highlight: {
            type: Boolean,
            default: true
        },
        clickHandler: {
            type: Function,
            default: null
        }
    },
    methods: {
        computeClass() {
            return [
                this.class,
                this.highlight && this.$route.path === this.to ? 'active-link' : ''
            ];
        },
        handleClick() {
            if (typeof this.clickHandler === 'function') {
                this.clickHandler();
            }
        }
    }
};
</script>

<style lang="css">
.active-link {
    background-color: var(--surface-hover);
    color: var(--primary-color);
}
a {
    text-decoration: none;
}
</style>