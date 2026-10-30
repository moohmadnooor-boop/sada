<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with demo users, categories and classic Arabic quotes.
     * Runs in one transaction so a failure never leaves half-seeded tables behind.
     */
    public function run(): void
    {
        DB::transaction(fn () => $this->seedDemoData());
    }

    private function seedDemoData(): void
    {
        $categories = collect([
            ['name' => 'حكمة', 'slug' => 'wisdom', 'icon' => '🦉', 'color' => '#4FD1C5'],
            ['name' => 'تحفيز', 'slug' => 'motivation', 'icon' => '🔥', 'color' => '#F6AD55'],
            ['name' => 'حب', 'slug' => 'love', 'icon' => '💗', 'color' => '#F687B3'],
            ['name' => 'حياة', 'slug' => 'life', 'icon' => '🌿', 'color' => '#68D391'],
            ['name' => 'شعر وأدب', 'slug' => 'poetry', 'icon' => '🪶', 'color' => '#B794F4'],
            ['name' => 'صداقة', 'slug' => 'friendship', 'icon' => '🤝', 'color' => '#63B3ED'],
            ['name' => 'تأمل', 'slug' => 'reflection', 'icon' => '🌙', 'color' => '#A0AEC0'],
        ])->mapWithKeys(fn (array $data) => [$data['slug'] => Category::create($data)]);

        $books = collect([
            ['title' => 'ديوان المتنبي', 'slug' => 'diwan-al-mutanabbi', 'author' => 'أبو الطيب المتنبي', 'era' => 'القرن الرابع الهجري', 'cover_color' => '#7A3E2B',
                'description' => 'ديوان شاعر العربية الأشهر، فيه الحكمة والفخر والمدح، وأبيات صارت أمثالاً تتردد على الألسنة منذ أكثر من ألف عام.'],
            ['title' => 'ديوان الإمام الشافعي', 'slug' => 'diwan-al-shafii', 'author' => 'الإمام الشافعي', 'era' => 'القرن الثاني الهجري', 'cover_color' => '#2F5D50',
                'description' => 'أشعار الإمام محمد بن إدريس الشافعي في الزهد والحكمة والصداقة وطلب العلم، بلغة صافية قريبة من القلب.'],
            ['title' => 'النبي', 'slug' => 'the-prophet', 'author' => 'جبران خليل جبران', 'era' => '1923م', 'cover_color' => '#3E4C7A',
                'description' => 'تأملات شعرية على لسان «المصطفى» في الحب والعطاء والحرية والموت، من أكثر الكتب ترجمةً وقراءةً في العالم.'],
            ['title' => 'الشوقيات', 'slug' => 'al-shawqiyyat', 'author' => 'أحمد شوقي', 'era' => '1898م – 1936م', 'cover_color' => '#8A6A1F',
                'description' => 'ديوان أمير الشعراء أحمد شوقي، يجمع قصائده في الوطن والحكمة والتاريخ والمديح.'],
            ['title' => 'مقدمة ابن خلدون', 'slug' => 'muqaddimah', 'author' => 'ابن خلدون', 'era' => '1377م', 'cover_color' => '#5B3A6B',
                'description' => 'مؤسس علم الاجتماع يشرح فيها نشوء الدول وسقوطها وطبائع العمران البشري، ولا تزال مرجعاً حياً حتى اليوم.'],
            ['title' => 'المثنوي', 'slug' => 'masnavi', 'author' => 'جلال الدين الرومي', 'era' => 'القرن السابع الهجري', 'cover_color' => '#1F5F73',
                'description' => 'منظومة الرومي الكبرى في التأمل والمحبة والسلوك، تروي الحكاية لتصل إلى المعنى.'],
        ])->mapWithKeys(fn (array $data) => [$data['slug'] => Book::create($data)]);

        $users = collect([
            ['name' => 'سارة العتيبي', 'email' => 'sara@sada.test', 'bio' => 'أجمع الكلمات التي تشبهني.'],
            ['name' => 'عبدالله القحطاني', 'email' => 'abdullah@sada.test', 'bio' => 'قارئ نهم وعاشق للشعر العربي.'],
            ['name' => 'نورة الشهري', 'email' => 'noura@sada.test', 'bio' => 'كل اقتباس رسالة لم تُرسل.'],
            ['name' => 'فيصل الحربي', 'email' => 'faisal@sada.test', 'bio' => 'أبحث عن الحكمة في التفاصيل الصغيرة.'],
            ['name' => 'ريم الزهراني', 'email' => 'reem@sada.test', 'bio' => null],
        ])->map(fn (array $data) => User::create($data + ['password' => 'password123']));

        $quotes = [
            ['على قدر أهل العزم تأتي العزائم، وتأتي على قدر الكرام المكارم', 'المتنبي', 'poetry', 'diwan-al-mutanabbi'],
            ['ما كل ما يتمنى المرء يدركه، تجري الرياح بما لا تشتهي السفن', 'المتنبي', 'life', 'diwan-al-mutanabbi'],
            ['دع الأيام تفعل ما تشاء، وطب نفساً إذا حكم القضاء', 'الإمام الشافعي', 'reflection', 'diwan-al-shafii'],
            ['ما طار طير وارتفع، إلا كما طار وقع', 'مثل عربي', 'wisdom'],
            ['من جدّ وجد، ومن زرع حصد', 'مثل عربي', 'motivation'],
            ['الصديق وقت الضيق', 'مثل عربي', 'friendship'],
            ['قيمة كل امرئ ما يحسنه', 'علي بن أبي طالب', 'wisdom'],
            ['الحب لا يعرف عمقه إلا ساعة الفراق', 'جبران خليل جبران', 'love', 'the-prophet'],
            ['إن الحياة عقيدة وجهاد', 'أحمد شوقي', 'life', 'al-shawqiyyat'],
            ['ومن لم يذق مرّ التعلم ساعة، تجرّع ذل الجهل طول حياته', 'الإمام الشافعي', 'motivation', 'diwan-al-shafii'],
            ['أعز مكان في الدنى سرج سابح، وخير جليس في الزمان كتاب', 'المتنبي', 'poetry', 'diwan-al-mutanabbi'],
            ['إذا غامرت في شرف مروم، فلا تقنع بما دون النجوم', 'المتنبي', 'motivation', 'diwan-al-mutanabbi'],
            ['سلامٌ على الدنيا إذا لم يكن بها صديقٌ صدوقٌ صادق الوعد منصفا', 'الإمام الشافعي', 'friendship', 'diwan-al-shafii'],
            ['الصمت حكمة وقليلٌ فاعله', 'مثل عربي', 'wisdom'],
            ['خير الكلام ما قلّ ودلّ', 'مثل عربي', 'wisdom'],
            ['أنت لست قطرة في المحيط، أنت المحيط كله في قطرة', 'جلال الدين الرومي', 'reflection', 'masnavi'],
            ['الأمل هو الشيء الوحيد الذي يمنحك القوة حين يخذلك كل شيء', null, 'motivation'],
            ['بعض الكلمات لا تُقال، بل يُسمع صداها في القلب طويلاً', null, 'reflection'],
            ['اقرأ كأنك ستعيش أبداً، واكتب كأنك ستغيب غداً', null, 'poetry'],
            ['ليس الغريب غريب الشام واليمن، إن الغريب غريب اللحد والكفن', 'الإمام الشافعي', 'reflection', 'diwan-al-shafii'],
            ['وإذا كانت النفوس كباراً، تعبت في مرادها الأجسام', 'المتنبي', 'motivation', 'diwan-al-mutanabbi'],
            ['المحبة لا تعطي إلا ذاتها، ولا تأخذ إلا من ذاتها', 'جبران خليل جبران', 'love', 'the-prophet'],
            ['لا تؤجل عمل اليوم إلى الغد', 'مثل عربي', 'motivation'],
            ['الوقت كالسيف إن لم تقطعه قطعك', 'مثل عربي', 'life'],
            ['من عرف نفسه فقد عرف ربه', 'أثر مأثور', 'reflection'],
            ['أحب الناس إليّ من أهدى إليّ عيوبي', 'عمر بن الخطاب', 'friendship'],
            ['ابتسم، فالحياة أقصر من أن نقضيها عابسين', null, 'life'],
            ['القلب الذي يحب حقاً لا ينسى أبداً', null, 'love'],
            ['المغلوب مولع أبداً بالاقتداء بالغالب في شعاره وزيّه ونحلته وسائر أحواله وعوائده', 'ابن خلدون', 'wisdom', 'muqaddimah'],
            ['الظلم مؤذن بخراب العمران', 'ابن خلدون', 'wisdom', 'muqaddimah'],

            // ديوان المتنبي
            ['الخيل والليل والبيداء تعرفني، والسيف والرمح والقرطاس والقلم', 'المتنبي', 'poetry', 'diwan-al-mutanabbi'],
            ['إذا أنت أكرمت الكريم ملكته، وإن أنت أكرمت اللئيم تمرّدا', 'المتنبي', 'wisdom', 'diwan-al-mutanabbi'],
            ['وإذا أتتك مذمّتي من ناقص، فهي الشهادة لي بأني كامل', 'المتنبي', 'poetry', 'diwan-al-mutanabbi'],
            ['أنام ملء جفوني عن شواردها، ويسهر الخلق جرّاها ويختصم', 'المتنبي', 'poetry', 'diwan-al-mutanabbi'],
            ['إذا رأيت نيوب الليث بارزة، فلا تظننّ أن الليث يبتسم', 'المتنبي', 'wisdom', 'diwan-al-mutanabbi'],
            ['ذو العقل يشقى في النعيم بعقله، وأخو الجهالة في الشقاوة ينعم', 'المتنبي', 'reflection', 'diwan-al-mutanabbi'],
            ['ومن يكُ ذا فمٍ مرٍّ مريض، يجد مرّاً به الماء الزلالا', 'المتنبي', 'wisdom', 'diwan-al-mutanabbi'],

            // ديوان الإمام الشافعي
            ['نعيب زماننا والعيب فينا، وما لزماننا عيب سوانا', 'الإمام الشافعي', 'reflection', 'diwan-al-shafii'],
            ['وعين الرضا عن كل عيب كليلة، ولكنّ عين السخط تبدي المساويا', 'الإمام الشافعي', 'friendship', 'diwan-al-shafii'],
            ['تغرّب عن الأوطان في طلب العلا، وسافر ففي الأسفار خمس فوائد', 'الإمام الشافعي', 'motivation', 'diwan-al-shafii'],
            ['إذا المرء لا يرعاك إلا تكلّفاً، فدعه ولا تكثر عليه التأسّفا', 'الإمام الشافعي', 'friendship', 'diwan-al-shafii'],
            ['ولرُبّ نازلة يضيق لها الفتى ذرعاً، وعند الله منها المخرج', 'الإمام الشافعي', 'reflection', 'diwan-al-shafii'],
            ['شكوت إلى وكيع سوء حفظي، فأرشدني إلى ترك المعاصي', 'الإمام الشافعي', 'wisdom', 'diwan-al-shafii'],

            // النبي — جبران خليل جبران
            ['أولادكم ليسوا أولاداً لكم، إنهم أبناء الحياة المشتاقة إلى نفسها', 'جبران خليل جبران', 'life', 'the-prophet'],
            ['العمل هو الحب وقد صار مرئياً', 'جبران خليل جبران', 'life', 'the-prophet'],
            ['إنكم تعطون قليلاً حين تعطون مما تملكون، فإذا أعطيتم من ذواتكم فذلك هو العطاء الحق', 'جبران خليل جبران', 'wisdom', 'the-prophet'],
            ['الفرح والحزن لا يفترقان، يأتيان معاً', 'جبران خليل جبران', 'reflection', 'the-prophet'],
            ['قفوا معاً، ولكن لا يقترب أحدكم من الآخر كثيراً، فأعمدة الهيكل تقوم متباعدة', 'جبران خليل جبران', 'love', 'the-prophet'],

            // الشوقيات — أحمد شوقي
            ['وإنما الأمم الأخلاق ما بقيت، فإن همُ ذهبت أخلاقهم ذهبوا', 'أحمد شوقي', 'wisdom', 'al-shawqiyyat'],
            ['قم للمعلم وفّه التبجيلا، كاد المعلم أن يكون رسولا', 'أحمد شوقي', 'poetry', 'al-shawqiyyat'],
            ['وما نيل المطالب بالتمني، ولكن تؤخذ الدنيا غلابا', 'أحمد شوقي', 'motivation', 'al-shawqiyyat'],
            ['دقات قلب المرء قائلة له: إن الحياة دقائق وثوانِ', 'أحمد شوقي', 'life', 'al-shawqiyyat'],
            ['وللحرية الحمراء باب، بكل يدٍ مضرّجة يُدقّ', 'أحمد شوقي', 'poetry', 'al-shawqiyyat'],

            // مقدمة ابن خلدون
            ['الإنسان مدنيّ بالطبع', 'ابن خلدون', 'reflection', 'muqaddimah'],
            ['التعليم للعلم من جملة الصنائع', 'ابن خلدون', 'wisdom', 'muqaddimah'],
            ['الملك غاية طبيعية للعصبية', 'ابن خلدون', 'wisdom', 'muqaddimah'],
            ['الحضارة غاية العمران ونهاية لعمره، وأنها مؤذنة بفساده', 'ابن خلدون', 'reflection', 'muqaddimah'],

            // المثنوي — جلال الدين الرومي
            ['استمع إلى الناي كيف يقصّ حكايته، ومن ألم الفراق يبثّ شكايته', 'جلال الدين الرومي', 'reflection', 'masnavi'],
            ['كل من بقي بعيداً عن أصله، يظل يبحث عن زمان وصله', 'جلال الدين الرومي', 'love', 'masnavi'],
        ];

        $createdQuotes = collect($quotes)->map(function (array $quote, int $index) use ($users, $categories, $books) {
            $model = $users[$index % $users->count()]->quotes()->create([
                'content' => $quote[0],
                'author' => $quote[1],
                'category_id' => $categories[$quote[2]]->id,
                'book_id' => isset($quote[3]) ? $books[$quote[3]]->id : null,
            ]);

            $model->forceFill(['created_at' => now()->subHours(($index + 1) * 7)])->save();

            return $model;
        });

        $createdQuotes->each(function (Quote $quote) use ($users) {
            $users->random(random_int(0, $users->count()))->each(
                fn (User $user) => $quote->likers()->attach($user->id, ['created_at' => now()->subDays(random_int(0, 10))])
            );
        });

        $comments = ['كلام من ذهب 👌', 'هذا الاقتباس لامسني', 'صدقت والله', 'من أجمل ما قرأت اليوم', 'يستحق الحفظ'];

        $createdQuotes->random(12)->each(function (Quote $quote) use ($users, $comments) {
            $commenter = $users->random();
            $quote->comments()->make(['body' => $comments[array_rand($comments)]])->user()->associate($commenter)->save();
        });

        $users->each(function (User $user) use ($users, $createdQuotes) {
            $user->following()->attach($users->where('id', '!=', $user->id)->random(2)->pluck('id'));
            $user->bookmarkedQuotes()->attach($createdQuotes->random(3)->pluck('id'));
        });
    }
}
