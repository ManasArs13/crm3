<style>
    .content {
        background-color: #f4f6f9;
    }

    #loadingIndicator {
        position: sticky;
        top: 650px;
        left: 800px;
        z-index: 1000;
    }

    #status {
        width: auto;
        height: 40px;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .button-block {
        height: 60px;
        border-radius: 3px;
        margin-left: 10px;
        display: flex;
        justify-content: start;
        align-items: center;
    }

    .buttons {
        width: 900px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    #buttons {
        width: 120px;
        height: 45px;
        background-color: darkgrey;
        font-weight: bold;
        font-family: sans-serif;
    }

    #buttons:hover {
        background-color: #c4c2c2;

    }

    .td-percent {
        min-width: 40px;
        max-width: 60px;
        height: 25px;
        background-color: rgb(74 222 128);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
    }

    .td-percent-red {
        min-width: 40px;
        max-width: 60px;
        height: 25px;
        background-color: rgb(248 113 113);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
    }

    .td-percent-yellow {
        min-width: 40px;
        max-width: 60px;
        height: 25px;
        background-color: rgb(234 179 8);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
    }
</style>