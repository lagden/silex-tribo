<?php
$app['title'] = "Tribo Interactive";
$app['dados'] = [
    "nome"=>"Tribo Interactive",
    "local"=>[
        "headquarter"=>[
            "endereco"=>"Rua Luigi Galvani, 70 - 13º",
            "cidade"=>"São Paulo",
            "estado"=>"SP",
            "cep"=>"04575-020",
            "telefone"=>"+55 11 55011351",
            "negocios"=>[
                "nome"=>"Leda Cichello",
                "email"=>"leda.cichello@tribointeractive.com.br",
            ],
        ],
        "filial"=>[
            "endereco"=>"Av. Beira Mar, 262 - Sala 802",
            "cidade"=>"Rio de Janeiro",
            "estado"=>"RJ",
            "cep"=>"20021-060",
            "telefone"=>"+55 21 38149650",
            "negocios"=>[
                "nome"=>"Ana Masagão",
                "email"=>"ana.masagao@tribointeractive.com.br",
            ],
        ]
    ],
    "quemsomos"=>[
        ["nome"=>"Raul Orfão", "cargo"=>"CEO", "imagem"=>"raul.jpg"],
        ["nome"=>"Roger Rocha", "cargo"=>"VP de criação", "imagem"=>"roger.jpg"],
        ["nome"=>"Edna Santos", "cargo"=>"Diretora de RH", "imagem"=>"edna.jpg"],
        ["nome"=>"Denis Takahashi", "cargo"=>"Diretor de TI", "imagem"=>"denis.jpg"],
        ["nome"=>"Leda Cichello", "cargo"=>"Diretora de novos negócios", "imagem"=>"leda.jpg"],
        ["nome"=>"Ricardo Schreier", "cargo"=>"Diretor de criação", "imagem"=>"ricardo.jpg"],
        ["nome"=>"Guilherme Soares", "cargo"=>"Diretor de criação", "imagem"=>"guilherme.jpg"],
        ["nome"=>"Alessandra Orrico", "cargo"=>"Diretora de Mídia", "imagem"=>"alessandra.jpg"],
        ["nome"=>"Renato Muller", "cargo"=>"Diretor de Planejamento", "imagem"=>"renato.jpg"],
        ["nome"=>"Tclea Klouri", "cargo"=>"Diretora de Atendimento", "imagem"=>"tclea.jpg"]
    ],
];

// Twitter
$app['twitter.key'] = '42T8fw5GWeqLesWQ3wNksA';
$app['twitter.secret'] = 'x05yNCG61V6MeycrZ5GVFAmkFkj28V99DuCrIvcjl8';
$app['twitter.request_token'] = 'https://api.twitter.com/oauth/request_token';
$app['twitter.authorize_url'] = 'https://api.twitter.com/oauth/authorize';
$app['twitter.access_token_url'] = 'https://api.twitter.com/oauth/access_token';
$app['twitter.callback_url'] = 'http://www.tribointeractive.com.br/twitter/oauth-callback';
$app['twitter.access_token'] = '44358342-y48yiWS6Qf8t0CS0cylJrc25Jot03rxwctSJ0u0ax';
$app['twitter.access_token_secret'] = 'fCqBSzFLolldtPrzVpuBNWfDzGUpUJEGXC6mIQEnak';
