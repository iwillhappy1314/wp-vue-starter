<template>
    <div id="wprs-tsm-frontend-app">
        <h2>Frontend App</h2>

        <router-link to="/">Home</router-link>
        <router-link to="/profile">Profile</router-link>

        <router-view />
    </div>
</template>

<script>
  export default {
    name: 'App'
  }
</script>

<style>

</style>