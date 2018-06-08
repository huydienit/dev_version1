<?php

namespace Api\Nhvv\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Api\Nhvv\App\Http\Resources\SettingResource;
use Illuminate\Support\Collection;
use Adtech\Core\App\Models\Role;
use Illuminate\Http\Response;
use Crypt;

class SettingController extends BaseController
{

    public function version()
    {
        $data = '{
          "version": 0.3
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');

//        $roles = Role::all();
//        return (SettingResource::collection($roles))->response()->setStatusCode(200)->setCharset('utf-8');
    }

    public function setting()
    {
        $data = '{
          "music": 1,
          "sound": 1,
          "settingCabin": {
            "ListSettingCabin": [
              {
                "star": 1,
                "count": 3,
                "gold": 0
              },
              {
                "star": 2,
                "count": 4,
                "gold": 640
              },
              {
                "star": 3,
                "count": 8,
                "gold": 8300
              },
              {
                "star": 4,
                "count": 12,
                "gold": 120400
              },
              {
                "star": 5,
                "count": 20,
                "gold": 250000
              }
            ]
          },
          "settingLevel": {
            "ListSettingLevel": [
              {
                "level": 1,
                "expRequire": 0,
                "goldReceive": 0,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 0,
                "ecoinReceive": 0
              },
              {
                "level": 2,
                "expRequire": 195,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 5,
                "medalReceive": 1,
                "ecoinReceive": 0
              },
              {
                "level": 3,
                "expRequire": 676,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 1,
                "ecoinReceive": 0
              },
              {
                "level": 4,
                "expRequire": 158,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 5,
                "medalReceive": 1,
                "ecoinReceive": 0
              },
              {
                "level": 5,
                "expRequire": 3072,
                "goldReceive": 2000,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 1,
                "ecoinReceive": 5
              },
              {
                "level": 6,
                "expRequire": 5275,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 5,
                "medalReceive": 1,
                "ecoinReceive": 0
              },
              {
                "level": 7,
                "expRequire": 8340,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 0,
                "ecoinReceive": 1
              },
              {
                "level": 8,
                "expRequire": 12411,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 0,
                "ecoinReceive": 1
              },
              {
                "level": 9,
                "expRequire": 17632,
                "goldReceive": 1000,
                "gemReceive": 0,
                "heartReceive": 0,
                "medalReceive": 1,
                "ecoinReceive": 5
              },
              {
                "level": 10,
                "expRequire": 24147,
                "goldReceive": 5000,
                "gemReceive": 0,
                "heartReceive": 5,
                "medalReceive": 1,
                "ecoinReceive": 5
              }
            ]
          },
          "settingCart": {
            "maxSlotCart": 9,
            "userDefaultCart": 3,
            "fastBuyGold": 5000,
            "ListSlotPrice": [
              {
                "idSlot": 0,
                "gold": 10
              },
              {
                "idSlot": 1,
                "gold": 110
              },
              {
                "idSlot": 2,
                "gold": 1120
              },
              {
                "idSlot": 3,
                "gold": 2320
              },
              {
                "idSlot": 4,
                "gold": 11230
              },
              {
                "idSlot": 5,
                "gold": 22350
              },
              {
                "idSlot": 6,
                "gold": 112380
              },
              {
                "idSlot": 7,
                "gold": 235110
              },
              {
                "idSlot": 8,
                "gold": 586210
              }
            ]
          },
          "settingFoodRecipe": {
            "ListSettingLevel": [
              {
                "id": 0,
                "expBase": 10,
                "goldBase": 1000,
                "index": 1.4
              },
              {
                "id": 1,
                "expBase": 5,
                "goldBase": 2000,
                "index": 1.4
              },
              {
                "id": 2,
                "expBase": 4,
                "goldBase": 3000,
                "index": 1.4
              },
              {
                "id": 3,
                "expBase": 3,
                "goldBase": 4000,
                "index": 1.4
              },
              {
                "id": 4,
                "expBase": 2,
                "goldBase": 5000,
                "index": 1.4
              }
            ]
          },
          "settingStaff": {
            "ListSettingStaff": [
              {
                "level": 1,
                "count": 3
              },
              {
                "level": 5,
                "count": 4
              },
              {
                "level": 10,
                "count": 5
              },
              {
                "level": 15,
                "count": 6
              }
            ]
          }
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listMaterial()
    {
        $data = '{
          "ListMaterial": [
            {
              "id": 0,
              "name": "Cà chua",
              "icon": "ca_chua",
              "description": "des"
            },
            {
              "id": 1,
              "name": "Đậu",
              "icon": "pea",
              "description": "des"
            },
            {
              "id": 2,
              "name": "Thịt lợn",
              "icon": "thit_lon",
              "description": "des"
            },
            {
              "id": 3,
              "name": "Bắp cải xanh",
              "icon": "bap_cai_xanh",
              "description": "des"
            },
            {
              "id": 4,
              "name": "Chân giò",
              "icon": "chan_gio",
              "description": "des"
            },
            {
              "id": 5,
              "name": "Trứng",
              "icon": "trung",
              "description": "des"
            },
            {
              "id": 6,
              "name": "Thịt bò",
              "icon": "thit_bo",
              "description": "des"
            },
            {
              "id": 7,
              "name": "Hành tây",
              "icon": "hanh_tay",
              "description": "des"
            },
            {
              "id": 8,
              "name": "Ngô",
              "icon": "ngo",
              "description": "des"
            },
            {
              "id": 9,
              "name": "Sữa",
              "icon": "sua",
              "description": "des"
            }
          ]
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listMaterialPackage()
    {
        $data = '{
          "listMaterialPackage": [
            {
              "id": 0,
              "name": "10",
              "count": 10,
              "gemItem": 0,
              "goldItem": 1000,
              "time": 50,
              "active": true,
              "gemActive": 0,
              "goldActive": 0,
              "MaterialActive": {
                "listMaterialCount": []
              }
            },
            {
              "id": 1,
              "name": "20",
              "count": 20,
              "gemItem": 0,
              "goldItem": 5000,
              "time": 40,
              "active": false,
              "gemActive": 0,
              "goldActive": 5000,
              "MaterialActive": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 0,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 5
                  },
                  {
                    "Key": {
                      "id": 1,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 5
                  }
                ]
              }
            }
          ]
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listFoodRecipe()
    {
        $data = '{
          "listFoodRecipe": [
            {
              "id": 0,
              "name": "Thịt sốt cà chua",
              "icon": "icon",
              "description": "des",
              "listMaterial": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 0,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 1
                  },
                  {
                    "Key": {
                      "id": 1,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 1
                  }
                ]
              },
              "timeCook": 10,
              "amountCook": 10,
              "goldCook": 10,
              "gemCook": 0,
              "expCooked": 10,
              "level": 10,
              "cooked": 10,
              "gold": 10,
              "gem": 0,
              "active": true,
              "goldActive": 0,
              "medalActive": 0,
              "gemActive": 0,
              "levelActice": 0,
              "expReceive": 0,
              "goldReceive": 0,
              "medalReceive": 0,
              "gemReceive": 0,
              "price": 1000
            },
            {
              "id": 1,
              "name": "Thit nướng",
              "icon": "icon",
              "description": "des",
              "listMaterial": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 2,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 2
                  },
                  {
                    "Key": {
                      "id": 3,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 2
                  }
                ]
              },
              "timeCook": 10,
              "amountCook": 10,
              "goldCook": 10,
              "gemCook": 0,
              "expCooked": 10,
              "level": 10,
              "cooked": 10,
              "gold": 10,
              "gem": 0,
              "active": true,
              "goldActive": 0,
              "medalActive": 0,
              "gemActive": 0,
              "levelActice": 0,
              "expReceive": 0,
              "goldReceive": 0,
              "medalReceive": 0,
              "gemReceive": 0,
              "price": 1000
            },
            {
              "id": 2,
              "name": "Nem rán",
              "icon": "icon",
              "description": "des",
              "listMaterial": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 5,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 3
                  },
                  {
                    "Key": {
                      "id": 4,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 3
                  }
                ]
              },
              "timeCook": 10,
              "amountCook": 10,
              "goldCook": 10,
              "gemCook": 0,
              "expCooked": 10,
              "level": 10,
              "cooked": 10,
              "gold": 10,
              "gem": 0,
              "active": true,
              "goldActive": 0,
              "medalActive": 0,
              "gemActive": 0,
              "levelActice": 0,
              "expReceive": 0,
              "goldReceive": 0,
              "medalReceive": 0,
              "gemReceive": 0,
              "price": 1000
            },
            {
              "id": 3,
              "name": "Thit bò xào hành tây",
              "icon": "icon",
              "description": "des",
              "listMaterial": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 6,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 4
                  },
                  {
                    "Key": {
                      "id": 7,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 4
                  }
                ]
              },
              "timeCook": 10,
              "amountCook": 10,
              "goldCook": 10,
              "gemCook": 0,
              "expCooked": 10,
              "level": 10,
              "cooked": 10,
              "gold": 10,
              "gem": 0,
              "active": true,
              "goldActive": 0,
              "medalActive": 0,
              "gemActive": 0,
              "levelActice": 0,
              "expReceive": 0,
              "goldReceive": 0,
              "medalReceive": 0,
              "gemReceive": 0,
              "price": 1000
            },
            {
              "id": 4,
              "name": "Mỳ bò",
              "icon": "icon",
              "description": "des",
              "listMaterial": {
                "listMaterialCount": [
                  {
                    "Key": {
                      "id": 8,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 5
                  },
                  {
                    "Key": {
                      "id": 9,
                      "name": "upgrade",
                      "icon": "icon",
                      "description": "des"
                    },
                    "Value": 5
                  }
                ]
              },
              "timeCook": 10,
              "amountCook": 10,
              "goldCook": 10,
              "gemCook": 0,
              "expCooked": 10,
              "level": 10,
              "cooked": 10,
              "gold": 10,
              "gem": 0,
              "active": true,
              "goldActive": 0,
              "medalActive": 0,
              "gemActive": 0,
              "levelActice": 0,
              "expReceive": 0,
              "goldReceive": 0,
              "medalReceive": 0,
              "gemReceive": 0,
              "price": 1000
            }
          ]
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listDecoration()
    {
        $data = '{
  "listDecoration": [
    {
      "id": 1,
      "name": "Bộ Salon",
      "icon": "icon",
      "keyDecoration": [
        0,
        1
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 1,
      "name": "Bàn đá",
      "icon": "",
      "keyDecoration": [
        0
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 2,
      "name": "Ghế đá",
      "icon": "",
      "keyDecoration": [
        1
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 3,
      "name": "Vách đá",
      "icon": "",
      "keyDecoration": [
        2
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 4,
      "name": "Vách thạch cao",
      "icon": "",
      "keyDecoration": [
        2
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 5,
      "name": "Lọ hoa hồng",
      "icon": "",
      "keyDecoration": [
        3,
        4
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 6,
      "name": "Tranh sơn dầu",
      "icon": "",
      "keyDecoration": [
        3,
        7
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 7,
      "name": "Cửa gỗ 2 cánh",
      "icon": "",
      "keyDecoration": [
        5
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 8,
      "name": "Cửa gỗ 1 cánh",
      "icon": "",
      "keyDecoration": [
        5
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 9,
      "name": "Cửa sổ loại 1",
      "icon": "",
      "keyDecoration": [
        6
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 10,
      "name": "Đầu sư tử",
      "icon": "",
      "keyDecoration": [
        7
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 11,
      "name": "Bếp",
      "icon": "",
      "keyDecoration": [
        8
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 12,
      "name": "tủ kính hoa văn",
      "icon": "",
      "keyDecoration": [
        9
      ],
      "star": true,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 13,
      "name": "Gạch đỏ",
      "icon": "",
      "keyDecoration": [
        13
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    },
    {
      "id": 14,
      "name": "Giấy đỏ",
      "icon": "",
      "keyDecoration": [
        14
      ],
      "star": false,
      "starDefault": 0,
      "description": "",
      "level": 0,
      "levelUnlock": 0,
      "point": 0,
      "gem": 0,
      "gold": 0,
      "heart": 0,
      "time": 0,
      "goldReceive": 0,
      "gemReceive": 0,
      "expReceive": 0,
      "sizeX": 0,
      "sizeY": 0,
      "price": 0
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listBundle()
    {
        $data = '{
  "list": [
    {
      "name": "animation",
      "version": 0,
      "hash": "c4797e3043864b61dd2c22fbe60dd355"
    },
    {
      "name": "bep",
      "version": 0,
      "hash": "f99f370cdf9d83129b241446e6cfa4c1"
    },
    {
      "name": "canvas",
      "version": 0,
      "hash": "d7a5141be5c88acbe88c5dbfe3378033"
    },
    {
      "name": "material",
      "version": 0,
      "hash": "b025f5413cd950df55595cb81a12ca55"
    },
    {
      "name": "music",
      "version": 0,
      "hash": "35f1572c7c207289294f7f966da29928"
    },
    {
      "name": "object",
      "version": 0,
      "hash": "15329b2e5dd4d16f144754e6b60d60cf"
    },
    {
      "name": "setting",
      "version": 0,
      "hash": "acb689ce2b96f42e9f3b07941c4bbfd1"
    },
    {
      "name": "sound",
      "version": 0,
      "hash": "e31bdd835a7559306832d5e0fc4c6d05"
    },
    {
      "name": "sprite",
      "version": 0,
      "hash": "3ba6ba29daa8a785f5227dc7b03c0165"
    },
    {
      "name": "text",
      "version": 0,
      "hash": "867da7e590cc7c483b31196959156924"
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listDecorationgroup()
    {
        $data = '{
  "listDecorationGroup": [
    {
      "id": 0,
      "name": "Trang trí sàn",
      "keyDecoration": [
        0,
        1,
        2,
        3,
        4
      ],
      "style": 0
    },
    {
      "id": 1,
      "name": "Trang trí tường",
      "keyDecoration": [
        5,
        6,
        7
      ],
      "style": 0
    },
    {
      "id": 2,
      "name": "Trang trí bếp",
      "keyDecoration": [
        8,
        9,
        10,
        11
      ],
      "style": 0
    },
    {
      "id": 3,
      "name": "Lát sàn",
      "keyDecoration": [
        12,
        13
      ],
      "style": 0
    },
    {
      "id": 4,
      "name": "Vip",
      "keyDecoration": [
        14,
        15,
        16
      ],
      "style": 1
    },
    {
      "id": 5,
      "name": "Theme",
      "keyDecoration": [
        17
      ],
      "style": 2
    },
    {
      "id": 0,
      "name": "Set trang trí",
      "keyDecoration": [
        18
      ],
      "style": 3
    },
    {
      "id": 0,
      "name": "Mở rộng",
      "keyDecoration": [
        19
      ],
      "style": 4
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listDecorationTag()
    {
        $data = '{
  "listDecorationKey": [
    {
      "id": 0,
      "name": "Ghế"
    },
    {
      "id": 1,
      "name": "Bàn"
    },
    {
      "id": 2,
      "name": "Vách ngăn"
    },
    {
      "id": 3,
      "name": "Trang bị"
    },
    {
      "id": 4,
      "name": "Cây cối"
    },
    {
      "id": 5,
      "name": "Cửa ra vào"
    },
    {
      "id": 6,
      "name": "Cửa sổ"
    },
    {
      "id": 7,
      "name": "Vật gắn tường"
    },
    {
      "id": 8,
      "name": "Máy nấu"
    },
    {
      "id": 9,
      "name": "Tủ trưng bày"
    },
    {
      "id": 10,
      "name": "Máy làm nước"
    },
    {
      "id": 11,
      "name": "Máy tính tiền"
    },
    {
      "id": 12,
      "name": "Gạch lát nền"
    },
    {
      "id": 13,
      "name": "Giấy dán tường"
    },
    {
      "id": 14,
      "name": "Vàng"
    },
    {
      "id": 15,
      "name": "Heart"
    },
    {
      "id": 16,
      "name": "Gem"
    },
    {
      "id": 17,
      "name": "Tất cả"
    },
    {
      "id": 18,
      "name": "Tất cả"
    },
    {
      "id": 19,
      "name": "Tất cả"
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listStaff()
    {
        $data = '{
  "ListStaffs": [
    {
      "id": 0,
      "name": "Hoàng Trung Thông",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 1,
      "name": "Bạch Cốt Tinh",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 2,
      "name": "Long Đại Ca",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 3,
      "name": "Tôn Ngộ Không",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 4,
      "name": "Sa hoàng",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 5,
      "name": "Ngọc Hoàng",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 6,
      "name": "Lâm Xung",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 7,
      "name": "Lý Thông",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 8,
      "name": "Lý Quỳ",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 9,
      "name": "Thạch Sanh",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 10,
      "name": "Công tôn sách",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    },
    {
      "id": 11,
      "name": "Triển Triêu",
      "description": "des",
      "icon": "icon",
      "type": 0,
      "rarity": 1,
      "speed": 5.5,
      "onemoreRatio": 0.5,
      "onemorex2Ratio": 0.5,
      "onemorex3Ratio": 0.5,
      "drinkRatio": 0.5,
      "decorativePoint": 30,
      "bonusPoint": 10,
      "time": 100,
      "goldPrice": 10,
      "gemPrice": 10
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listStaffPosition()
    {
        $data = '{
  "ListStaffPositions": [
    {
      "id": 0,
      "name": "Thu ngân",
      "description": "des"
    },
    {
      "id": 1,
      "name": "Nhân viên",
      "description": "des"
    }
  ]
}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listStorageTab()
    {
        $data = '{"ListTab":[{"Key":0,"Value":"Món ăn"},{"Key":1,"Value":"Ðặc biệt"}]}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listMaterialMarketTab()
    {
        $data = '{"ListTab":[{"Key":0,"Value":"Món ăn"},{"Key":1,"Value":"Ðặc biệt"}]}';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function listRecipeTab()
    {
        $data = '{
          "ListTab": [
            {
              "Key": 0,
              "Value": "CƠ BẢN"
            },
            {
              "Key": 1,
              "Value": "VIP"
            },
            {
              "Key": 2,
              "Value": "THẦN BÍ"
            }
          ]
        }';

        return response($data)->setStatusCode(200)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function demo(Request $request)
    {
        $encrypted = encrypt('Hello world.');
        $decrypted = decrypt($encrypted);
        echo $encrypted;
        echo '<br>';
        echo $decrypted;
        echo '<br>';
        $encrypted = Crypt::encryptString('Hello world.');
        $decrypted = Crypt::decryptString($encrypted);
        echo $encrypted;
        echo '<br>';
        echo $decrypted;
    }

}
