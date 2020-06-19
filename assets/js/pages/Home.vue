<template>
    <section class="hero is-medium">
        <div class="hero-body">
            <div class="container has-text-centered">
                <img width="64" :src="logo" alt="cheeper" />
                <h1 class="is-size-2 has-text-info-dark logo-text">
                    Cheeper
                </h1>
                <h2>A twitter's clone</h2>
                <p class="buttons is-centered mt-4">
                    <a href="#" class="button is-link">
                        <span>Sign Up</span>
                    </a>
                    <a href="#" class="button is-link">
                        <span>Log in</span>
                    </a>
                </p>
            </div>
        </div>
    </section>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import logo from "@/../images/cheeper.svg";

@Component
export default class Home extends Vue {
    logo = logo;
}
</script>
