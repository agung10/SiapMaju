<style>
    .search-input-container{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        margin:10px;
        border-radius:10px;
        background:#d3d3d3;
    }

    .label-container{
        margin:50px 0px 0px 0px;
    }

    .search-input-wrapper{
        display:flex;
        flex-direction:row;
        justify-content:center;
        align-items:center;
        padding:10px;
    }

    .input-data{
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
        align-items:flex-start;
        margin:10px;
    }

    .button-container{
        display:flex;
        padding:10px;
    }

    .result-container{
        padding:10px;
        margin-top:50px;
        display:none;
    }

    @media screen and (max-width:600px){
        .search-input-wrapper input{
            width:150px;
        }

        .input-data input{
            width:130px;
        }
    }
</style>