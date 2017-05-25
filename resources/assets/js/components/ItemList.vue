<template>
    <div class="table-responsive">
        <table class="table table-striped table-hover folder-table">

            <thead>
                <tr>
                    <th class="preview-column"></th>
                    <th>Name</th>
                    <th>Size</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <stack-folder-item v-for="folder in folders" :key="folder.id" :item="folder"></stack-folder-item>
                <stack-file-item v-for="file in files" :key="file.id" :item="file"></stack-file-item>
            </tbody>

        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                folders: [],
                files: [],
            }
        },

        created() {
            this.folders = JSON.parse(window.folders);
            this.files = JSON.parse(window.files);
        },
    }
</script>
