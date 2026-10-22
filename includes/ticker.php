<?php
$tickerItems = [
    ['symbol' => 'NIFTY 50', 'name' => 'NSE Benchmark', 'value' => 25842.10, 'change' => 142.30, 'changePercent' => 0.55, 'prefix' => '', 'suffix' => ''],
    ['symbol' => 'SENSEX', 'name' => 'BSE Benchmark', 'value' => 84560.85, 'change' => 418.20, 'changePercent' => 0.50, 'prefix' => '', 'suffix' => ''],
    ['symbol' => 'NIFTY BANK', 'name' => 'Banking Index', 'value' => 53780.40, 'change' => 210.65, 'changePercent' => 0.39, 'prefix' => '', 'suffix' => ''],
    ['symbol' => 'NIFTY IT', 'name' => 'Technology', 'value' => 42190.15, 'change' => -85.40, 'changePercent' => -0.20, 'prefix' => '', 'suffix' => ''],
    ['symbol' => 'NIFTY MIDCAP 150', 'name' => 'Midcap Growth', 'value' => 21430.70, 'change' => 188.50, 'changePercent' => 0.89, 'prefix' => '', 'suffix' => ''],
    ['symbol' => 'GOLD (10g)', 'name' => 'MCX Spot', 'value' => 76420, 'change' => 310.00, 'changePercent' => 0.41, 'prefix' => '₹', 'suffix' => ''],
    ['symbol' => '10Y G-SEC YIELD', 'name' => 'Govt Bond Yield', 'value' => 6.84, 'change' => -0.02, 'changePercent' => -0.29, 'prefix' => '', 'suffix' => '%'],
    ['symbol' => 'USD / INR', 'name' => 'Forex Spot', 'value' => 83.84, 'change' => 0.03, 'changePercent' => 0.04, 'prefix' => '₹', 'suffix' => ''],
    ['symbol' => 'BRENT CRUDE', 'name' => 'Commodity', 'value' => 74.20, 'change' => -0.45, 'changePercent' => -0.60, 'prefix' => '$', 'suffix' => '']
];
?>

<div class="relative bg-[#05151E] border-y border-white/10 overflow-hidden shadow-inner select-none z-20 py-3">
    <!-- Static live badge on the left for context on larger screens -->
    <div class="hidden lg:flex absolute left-0 top-0 bottom-0 z-30 bg-[#05151E] items-center px-4 border-r border-white/10">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#C9F24A] animate-ping"></span>
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#C9F24A]">MARKET PULSE</span>
        </div>
    </div>

    <!-- Marquee Track -->
    <div class="flex overflow-hidden whitespace-nowrap pl-0 lg:pl-36">
        <div id="tickerTrack" class="inline-flex items-center gap-8 animate-marquee hover:[animation-play-state:paused]">
            <?php for ($loop = 0; $loop < 2; $loop++): ?>
                <?php foreach ($tickerItems as $item): 
                    $isPositive = $item['change'] >= 0;
                ?>
                <div class="ticker-item inline-flex items-center gap-3 px-3 py-1 rounded-xl bg-white/5 border border-white/5 hover:border-white/15 transition-all" data-symbol="<?= htmlspecialchars($item['symbol']) ?>">
                    <div class="text-left">
                        <div class="text-xs font-bold text-white tracking-wide"><?= htmlspecialchars($item['symbol']) ?></div>
                        <div class="text-[10px] text-white/50"><?= htmlspecialchars($item['name']) ?></div>
                    </div>
                    <div class="text-right">
                        <div class="ticker-value text-xs font-bold text-white font-mono">
                            <?= $item['prefix'] ?><?= number_format($item['value'], $item['value'] < 100 ? 2 : 2) ?><?= $item['suffix'] ?>
                        </div>
                        <div class="ticker-change flex items-center justify-end gap-0.5 text-[10px] font-bold <?= $isPositive ? 'text-[#C9F24A]' : 'text-rose-400' ?>">
                            <i data-lucide="<?= $isPositive ? 'trending-up' : 'trending-down' ?>" class="w-2.5 h-2.5"></i>
                            <span><?= $isPositive ? '+' : '' ?><?= number_format($item['changePercent'], 2) ?>%</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
</div>
