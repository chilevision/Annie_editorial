<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rundowns;

class RundownsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = ["Want More Money? Start TV", "The Truth Is You Are Not The Only Person Concerned About TV", "Heres A Quick Way To Solve A Problem with TV", "Using 7 TV Strategies Like The Pros", "How To Use TV To Desire", "Quick and Easy Fix For Your TV", "Cracking The TV Code", "If TV Is So Terrible, Why Dont Statistics Show It?", "How To Learn TV", "The Lazy Mans Guide To TV", "How To Sell TV", "Top 3 Ways To Buy A Used TV", "Lies And Damn Lies About TV", "The Anthony Robins Guide To TV", "Avoid The Top 10 TV Mistakes", "Get The Most Out of TV and Facebook", "Find Out Now, What Should You Do For Fast TV?", "5 Ways Of TV That Can Drive You Bankrupt - Fast!", "The Philosophy Of TV", "What You Can Learn From Bill Gates About TV", "How To Become Better With TV In 10 Minutes", "The Ultimate Guide To TV", "5 Brilliant Ways To Teach Your Audience About TV", "Master (Your) TV in 5 Minutes A Day", "How To Earn $398/Day Using TV", "5 Romantic TV Ideas", "What Can You Do About TV Right Now", "The Untapped Gold Mine Of TV That Virtually No One Knows About", "Here Is What You Should Do For Your TV", "Are You Embarrassed By Your TV Skills? Heres What To Do", "Some People Excel At TV And Some Dont - Which One Are You?", "5 Simple Steps To An Effective TV Strategy", "Picture Your TV On Top. Read This And Make It So", "Does TV Sometimes Make You Feel Stupid?", "TV: Do You Really Need It? This Will Help You Decide!", "10 Things You Have In Common With TV", "The Best Way To TV", "Fast-Track Your TV", "Why TV Succeeds", "Are You Making These TV Mistakes?", "TV An Incredibly Easy Method That Works For All", "Believe In Your TV Skills But Never Stop Improving", "How To Handle Every TV Challenge With Ease Using These Tips", "What Can Instagramm Teach You About TV", "What Alberto Savoia Can Teach You About TV", "Why You Really Need (A) TV", "The TV That Wins Customers", "Should Fixing TV Take 60 Steps?", "10 Ways To Immediately Start Selling TV", "5 Brilliant Ways To Use TV"];

        foreach ($array as $key => $value) {
            $starttime = mt_rand(1262055681,time());
            $stoptime = $starttime + 1800;
            Rundowns::create([
                'user_id' => 1,
                'title' => $value,
                'starttime' => date('Y-m-d H:i:s', $starttime),
                'stoptime'  => date('Y-m-d H:i:s', $stoptime),
                'duration'  => 1800,
            ]);
        }
    }
}