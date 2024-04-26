<!--begin::Global Config(global config for global JS scripts)-->
<script>
            var KTAppSettings = {
    "breakpoints": {
        "sm": 576,
        "md": 768,
        "lg": 992,
        "xl": 1200,
        "xxl": 1400
    },
    "colors": {
        "theme": {
            "base": {
                "white": "#ffffff",
                "primary": "#3699FF",
                "secondary": "#E5EAEE",
                "success": "#1BC5BD",
                "info": "#8950FC",
                "warning": "#FFA800",
                "danger": "#F64E60",
                "light": "#E4E6EF",
                "dark": "#181C32"
            },
            "light": {
                "white": "#ffffff",
                "primary": "#E1F0FF",
                "secondary": "#EBEDF3",
                "success": "#C9F7F5",
                "info": "#EEE5FF",
                "warning": "#FFF4DE",
                "danger": "#FFE2E5",
                "light": "#F3F6F9",
                "dark": "#D6D6E0"
            },
            "inverse": {
                "white": "#ffffff",
                "primary": "#ffffff",
                "secondary": "#3F4254",
                "success": "#ffffff",
                "info": "#ffffff",
                "warning": "#ffffff",
                "danger": "#ffffff",
                "light": "#464E5F",
                "dark": "#ffffff"
            }
        },
        "gray": {
            "gray-100": "#F3F6F9",
            "gray-200": "#EBEDF3",
            "gray-300": "#E4E6EF",
            "gray-400": "#D1D3E0",
            "gray-500": "#B5B5C3",
            "gray-600": "#7E8299",
            "gray-700": "#5E6278",
            "gray-800": "#3F4254",
            "gray-900": "#181C32"
        }
    },
    "font-family": "Poppins"
};
        </script>
			<!--end::Global Config-->
			<!--begin::Global Theme Bundle(used by all pages)-->
			<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
			<script src="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
			<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
            @stack('custom-script')
            <script src="{{asset('assets/js/pages/features/custom/spinners.js')}}"></script>
			<!--end::Global Theme Bundle-->
			<!--begin::Page Vendors(used by this page)-->
			<script src="{{asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
            <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
            <script src="{{asset('assets/plugins/custom/datatables/dataTables.buttons.min.js')}}"></script>
            <script src="{{asset('assets/plugins/custom/datatables/buttons.print.min.js')}}"></script>
            <script src="{{ asset('js/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
            <script src="{{ asset('js/momentJS/moment-with-locales.min.js') }}"></script>
			<!--end::Page Vendors-->
			<!--begin::Page Scripts(used by this page)-->
			<script src="{{asset('assets/js/pages/widgets.js')}}"></script>
			<!--end::Page Scripts-->
        <script>
            const goBack = () => {
                let redirect    = $('li.current-active').find('a').attr('href');
                window.location = redirect;
            }

            // Image Preview Script
                const imageField = document.querySelector('input[name="image"]')

                if(imageField){

                    imageField.addEventListener('change',(event)=> {
                        const output = document.querySelector('.imagePicture');

                        output.src = URL.createObjectURL(event.target.files[0]);
                        output.onload = () => {
                            URL.revokeObjectURL(output.src)
                        }
                    });
                }

                // Pengumuman Image

                    const pengumumanImageField = document.querySelectorAll('input[name="image1"],input[name="image2"],input[name="image3"]')

                    if(pengumumanImageField){

                        pengumumanImageField.forEach(el => 
                                                        el.addEventListener('change',(event) => {
                                                            const output = el.parentElement
                                                                             .previousSibling
                                                                             .previousSibling
                                                                             .previousSibling
                                                                             .previousSibling
                                                                             .previousSibling
                                                                             .previousSibling
                                                                             .firstChild
                                                                             .nextSibling;

                                                            output.src  = URL.createObjectURL(event.target.files[0]);

                                                            if(output.src === null){
                                                                return;
                                                            }
                                                            
                                                            output.onload = () => {
                                                                URL.revokeObjectURL(output.src);
                                                            }
                                                        })
                                                    )
                    }

                // End Pengumuman Image

            // End Image Preview Script

            // datepicker
                  const dateInput = document.querySelectorAll('.date-input')
                  moment.locale('id')

                  if(dateInput){
                      dateInput.forEach(node => {

                        // prevent user from typing date
                        node.addEventListener('keypress',(e) => {
                            e.preventDefault()
                        })

                        datepicker(node,{
                           customMonths:['Januari','Febuari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                           customDays:['Minggu','Senin','Selasa','Rabu','Kamis',"Jum'at",'Sabtu'],
                           onSelect:(instance,date) => {
                               const el = instance.el
                               el.value = moment(date).format('D-M-YYYY')
                           }
                        })

                      })

                    //datepicker width
                    document.querySelectorAll('.qs-datepicker-container').forEach(node => {
                        const widthValue = screen.width > 600 ? '400px' : '300px'

                        node.style.width = widthValue
                    })
                  }

                  
            //end datepicker
        </script>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/5d6606bc77aa790be33116f9/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();
        </script>
        <!--End of Tawk.to Script-->