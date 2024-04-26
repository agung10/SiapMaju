<style>


    .filter-blok-container{
        border-radius:10px;
        margin-bottom:10px;
        background:#fff;
        padding:10px;
        display:flex;
        justify-content:flex-start;
        align-items:flex-start;
        width:100%;
    }

    .blok-input-wrapper{
        display:flex;
        flex-direction:row;
        
    }
    
    .reset-filter{
        width:200px;
        margin-left:10px;
    }

    #mapid{
        height:460px;
    }

    .no-data-container{
        display:flex;
        justify-self:flex-start;
        justify-content:center;
        align-items:center;
    }

    

    .leaflet-popup-content{
        width:500px !important;
    }

    .leaflet-popup-content-wrapper{
        max-height:300px !important;
        width:280px;
        overflow:scroll;
        overflow-x:hidden;
      }

    .detail-blok-container{
        display:flex;
        flex-direction:column;
        justify-content:flex-start;
        align-items:flex-start;
        padding:10px;
    }

    .detail-warga-container{
        display:flex;
        flex-direction:column;
    }

    .detail .warga,.umkm{
        display:flex;
        flex-direction:row;
        justify-content:center;
        align-items:center;
        margin:0px 10px 10px 10px;
        background:#F1F1F1;
        padding:10px;
        border-radius:10px;
        width:200px;
        word-break:break-word;
    }
    .detail .umkm .list{
        display: flex;
        flex-direction:row;
    }
    .detail .umkm{
        flex-direction:column;
    }

    .avatar{
        width:50px;
        height:50px;
        margin:0px 10px 0px 10px;
        border-radius:25px;
    }

    .logo-umkm{
        width:25px;
        height:25px;
        margin:0px 10px 0px 10px;
    }

    .nama-warga, .nama-umkm{
        width:100px;
    }

    .RW{
        font-weight:bold;
        color:blue;
    }

    .RT{
        font-weight:bold;
        color:red;
    }

    .leaflet-marker-icon{
        width:20px !important;
        height:25px !important;
    }

    .color-legend-wrapper{
        display:flex;
        flex-direction:column;
        margin:20px 0px 20px 0px;
        background:#fff;
        padding:10px;
        border-radius:10px;
    }

    .color-legend-container{
        display:flex;
        flex-direction:row;
        background:#fff;
        padding:10px 0px 10px 0px;
        border-radius:10px;
    }

    .color-legend{
        display:flex;
        flex-direction:row;
        justify-content:center;
        align-items:center;
    }
        /* ScrollBar CSS */

        .leaflet-popup-content-wrapper::-webkit-scrollbar {
            width: 8px;
        }   
    
        /* Handle */
        .leaflet-popup-content-wrapper::-webkit-scrollbar-thumb {
            background: #898888; 
            border-radius: 10px;
        }

        /* Handle on hover */
        .leaflet-popup-content-wrapper::-webkit-scrollbar-thumb:hover {
            background: #474747; 
        }

        /* End ScrollBar CSS */

    @media screen and (max-width: 600px){
        #mapid{
        height:400px;
      }

      .leaflet-popup{
          height:300px !important;
      }

      .leaflet-popup-content-wrapper{
        height:300px !important;
        width:250px;
        overflow:scroll;
        overflow-x:hidden;
      }

      .detail-warga-container{
        display:flex;
        flex-direction:column;  
       }
    }
    
</style>