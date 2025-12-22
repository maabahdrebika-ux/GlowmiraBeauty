<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
{
    $addresses = [
        ['name' => 'طرابلس', 'nameen' => 'Tripoli'],
        ['name' => 'بنغازي', 'nameen' => 'Benghazi'],
        ['name' => 'مصراتة', 'nameen' => 'Misrata'],
        ['name' => 'الزاوية', 'nameen' => 'Zawiya'],
        ['name' => 'سبها', 'nameen' => 'Sebha'],
        ['name' => 'سرت', 'nameen' => 'Sirte'],
        ['name' => 'البيضاء', 'nameen' => 'Al Bayda'],
        ['name' => 'زليتن', 'nameen' => 'Zliten'],
        ['name' => 'درنة', 'nameen' => 'Derna'],
        ['name' => 'اجدابيا', 'nameen' => 'Ajdabiya'],
        ['name' => 'غريان', 'nameen' => 'Gharyan'],
        ['name' => 'طبرق', 'nameen' => 'Tobruk'],
        ['name' => 'الكفرة', 'nameen' => 'Kufra'],
        ['name' => 'يفرن', 'nameen' => 'Yefren'],
        ['name' => 'نالوت', 'nameen' => 'Nalut'],
        ['name' => 'شحات', 'nameen' => 'Shahat'],
        ['name' => 'براك الشاطئ', 'nameen' => 'Brak al-Shati'],
        ['name' => 'وادي عتبة', 'nameen' => 'Wadi Atba'],
        ['name' => 'مرزق', 'nameen' => 'Murzuq'],
        ['name' => 'غات', 'nameen' => 'Ghat'],
        ['name' => 'زوارة', 'nameen' => 'Zuwara'],
        ['name' => 'المرج', 'nameen' => 'Al Marj'],
        ['name' => 'مسلاتة', 'nameen' => 'Msallata'],
        ['name' => 'الخمس', 'nameen' => 'Al Khums'],
        ['name' => 'ترهونة', 'nameen' => 'Tarhuna'],
        ['name' => 'بني وليد', 'nameen' => 'Bani Walid'],
        ['name' => 'الواحات', 'nameen' => 'Al Wahat'],
        ['name' => 'الجفرة', 'nameen' => 'Al Jufra'],
        ['name' => 'الجميل', 'nameen' => 'Al Jamil'],
        ['name' => 'رقدالين', 'nameen' => 'Ragdalin'],
        ['name' => 'العجيلات', 'nameen' => 'Al Ajaylat'],
        ['name' => 'الرياينة', 'nameen' => 'Al Rayayna'],
        ['name' => 'القره بوللي', 'nameen' => 'Qarabulli'],
        ['name' => 'قصر بن غشير', 'nameen' => 'Qasr bin Ghashir'],
        ['name' => 'السواني', 'nameen' => 'As Sawani'],
        ['name' => 'الماية', 'nameen' => 'Al Maya'],
        ['name' => 'الزنتان', 'nameen' => 'Zintan'],
        ['name' => 'الرجبان', 'nameen' => 'Rajban'],
        ['name' => 'الاصابعة', 'nameen' => 'Asabah'],
        ['name' => 'الشكشوك', 'nameen' => 'Ashkashuk'],
        ['name' => 'الحرابة', 'nameen' => 'Al Haraba'],
        ['name' => 'القلعة', 'nameen' => 'Al Qalaa'],
        ['name' => 'بئر الغنم', 'nameen' => 'Bir al-Ghanam'],
        ['name' => 'بئر الأشهب', 'nameen' => 'Bir al-Ashhab'],
        ['name' => 'أم الأرانب', 'nameen' => 'Umm al Aranib'],
        ['name' => 'الفقهاء', 'nameen' => 'Al Fuqaha'],
    ];

    DB::table('addresses')->insert($addresses);
}

}
