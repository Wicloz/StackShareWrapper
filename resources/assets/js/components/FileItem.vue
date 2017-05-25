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
            <a :href="item.url_hash + '?dl=1'" title="Download">
                <img class="share-icon pull-right" src="/media/icons/download.svg" alt="download">
            </a>
            <a :href="item.url_hash + '?full=1'" title="Share">
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
