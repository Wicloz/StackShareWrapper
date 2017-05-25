<template>
    <tr>
        <td>
            <img class="thumbnail preview-thumbnail" :src="item.file_thumbnail" alt="">
        </td>
        <td>
            <a class="item-name item-center-vertical" :href="item.url_slug">{{ item.name }}</a>
        </td>
        <td>
            <span class="item-center-vertical">{{ item.human_size }}</span>
        </td>

        <td>
            <img class="share-icon pull-right invisible" src="/media/icons/download.svg" alt="">
            <a :href="item.url_hash" title="Permalink">
                <img class="share-icon pull-right" src="/media/icons/link.svg" alt="link">
            </a>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            item: {required: true},
        },
    }
</script>
