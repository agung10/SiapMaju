<?php

use Illuminate\Database\Seeder;
use App\Models\Tentang\Profile;

class ProfileSeeder extends Seeder
{
   
    public function run()
    {
        Profile::create([
            'isi_profile' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tincidunt est id massa ullamcorper vehicula. Etiam ultricies tristique enim quis interdum. Aliquam vulputate posuere turpis eu vestibulum. Sed volutpat tellus ut urna porta, sit amet ultrices orci sollicitudin. Sed luctus diam nec purus consectetur, in condimentum elit placerat. Integer tempor varius sapien. Proin sit amet ornare arcu, sed laoreet diam. Phasellus fermentum enim id pellentesque accumsan. Nunc sit amet pharetra diam, sit amet sollicitudin augue. Nunc in tristique lectus. Aliquam erat volutpat. Sed pretium lectus ut nibh venenatis, quis mollis ex blandit. Sed convallis turpis augue, ac varius augue lobortis vitae. In hac habitasse platea dictumst.',
            'gambar_profile' => 'profile1.jpg',
        ]);
    }
}
