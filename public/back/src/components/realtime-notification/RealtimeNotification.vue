<template>
    <div class="notification-wrapper bg-white py-10 position-fixed">
        <ul class="list-unstyled mb-0">
            <li><a href="#">dfsdfdsf sd sad sad sad sad </a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sadf gfgg fg fdg  </a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sad d fgfdg fdg fdgf</a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sad </a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sad </a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sad </a></li>
            <li><a href="#">dfsdfdsf sd sad sad sad sad </a></li>
        </ul>
    </div>
</template>

<script>
export default {

}
</script>

<style>
    .notification-wrapper {
        border-radius: 0 0 10px 10px;
        max-width: 250px;
        min-width: 150px;
        z-index: 10;
        right: 130px;
        box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    }

    .notification-wrapper ul {
        width: 200px;
    }
    .notification-wrapper ul li {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-bottom: 1px dashed #ddd;
        cursor: pointer;
        font-size: 14px;
    }

    .notification-wrapper ul li:last-of-type {
        border-bottom: 0;
    }

    .notification-wrapper ul li a {
        color: #000;
        padding: 5px 8px;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .notification-wrapper ul li a:hover {
        background: #3a7afe;
        color: #fff;
    }

    @media (max-width: 475px){
        .notification-wrapper {
            right: 40px;
        }
    }
</style>