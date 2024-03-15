<template>
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">{{ titleText }}</h4>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    export default {
        name: 'Title',
        props: {
            titleText: String
        }
    }
</script>