<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Swagger(
 *      schemes={"http", "https"},
 *      @OA\Info(
 *          version="1.0.0",
 *          title="L5 Swagger API",
 *          description="L5 Swagger API description",
 *          @OA\Contact(
 *              email="duongvanhai2712@gmail.com"
 *          )
 *      ),
 *  )
 */
/**
 *
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 * )
 *
 */

//ADMIN
//Province
/**
 * @OA\Get(
 * 		path = "/api/v1/get-all-provinces",
 * 		tags = {"Province"},
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		summary = "Trả về tất cả các tỉnh",
 * 		description = "Trả về tất cả các tỉnh",
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/get-all-districts",
 * 		tags = {"Province"},
 * 		summary = "Trả về tất cả các huyện",
 * 		description = "Trả về tất cả các huyện",
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/detail-province",
 * 		tags = {"Province"},
 * 		summary = "Trả về các huyện trong một tỉnh",
 * 		description = "Trả về các huyện trong một tỉnh",
 *      @OA\Parameter(
 *          name="id",
 *          required=true,
 *          in="query",
 *          description="ID của tỉnh",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Province

//Account
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/sign-up",
 * 		tags = {"Account"},
 * 		summary = "Đăng ký tài khoản",
 * 		description = "Đăng ký tài khoản Admin để vào ứng dụng ứng dụng",
 *      @OA\Parameter(
 *          name="name",
 *          required=true,
 *          in="query",
 *          description="Tên người dùng",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="email",
 *          required=true,
 *          in="query",
 *          description="Email",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="project_name",
 *          required=true,
 *          in="query",
 *          description="Tên dự án",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/create-new-password",
 * 		tags = {"Account"},
 * 		summary = "Gửi mail nhận link reset mật khẩu",
 * 		description = "Gửi mail nhận link reset mật khẩu",
 *      @OA\Parameter(
 *          name="email",
 *          required=true,
 *          in="query",
 *          description="Email",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/reset-password",
 * 		tags = {"Account"},
 * 		summary = "Xác nhận vào form đổi mật khẩu",
 * 		description = "Xác nhận vào form đổi mật khẩu",
 *      @OA\Parameter(
 *          name="token",
 *          description="Token",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/update-new-password",
 * 		tags = {"Account"},
 * 		summary = "Thay đổi mật khẩu mới",
 * 		description = "Thay đổi mật khẩu mới",
 *      @OA\Parameter(
 *          name="token",
 *          required=true,
 *          in="query",
 *          description="Token",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/sign-in",
 * 		tags = {"Account"},
 * 		summary = "Đăng nhập tài khoản",
 * 		description = "Đăng nhập với tư cách là Admin vào ứng dụng",
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/sign-out",
 * 		summary="Đăng xuất tài khoản",
 * 		tags={"Account"},
 * 		description="Đăng xuất tài khoản Admin khỏi ứng dụng",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/account/detail",
 * 		tags = {"Account"},
 * 		summary = "Chi tiết tài khoản",
 * 		description = "Chi tiết tài khoản",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của người dùng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/account/update",
 * 		tags = {"Account"},
 * 		summary = "Cập nhật tài khoản",
 * 		description = "Cập nhật tài khoản",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="username",
 *          description="Tên tài khoản",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="password",
 *          description="Mật khẩu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="profile_image",
 *          required=false,
 *          in="query",
 *          description="File ảnh của tài khoản",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Account

//Brand
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/brand/list",
 * 		tags = {"Brand"},
 * 		summary = "Danh sách các nhãn hàng",
 * 		description = "Danh sách các nhãn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/brand/create",
 * 		tags = {"Brand"},
 * 		summary = "Tạo mới nhãn hàng",
 * 		description = "Tạo mới nhãn hàng",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của nhãn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Mô tả của nhãn hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "subBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "name",
 *                     type="string",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn con của nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/brand/detail",
 * 		tags = {"Brand"},
 * 		summary = "Chi tiết nhãn hàng",
 * 		description = "Chi tiết nhãn hàng",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của nhãn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/brand/update",
 * 		tags = {"Brand"},
 * 		summary = "Cập nhật nhãn hàng",
 * 		description = "Cập nhật nhãn hàng",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của nhãn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của nhãn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Mô tả của nhãn hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "idsDelSubBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của nhãn hàng cần xóa"
 *      ),
 *     @OA\Parameter(
 *          name = "subBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "name",
 *                     type="string",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn con của nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/brand/delete",
 * 		tags = {"Brand"},
 * 		summary = "Xóa nhãn hàng",
 * 		description = "xóa nhãn hàng",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/brand/add-sub-brand",
 * 		tags = {"Brand"},
 * 		summary = "Thêm nhãn con",
 * 		description = "Thêm nhãn con",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của nhãn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "subBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "name",
 *                     type="string",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn con của nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/brand/get-all-brand",
 * 		tags = {"Brand"},
 * 		summary = "Lấy tất cả nhãn hàng",
 * 		description = "Lấy tất cả nhãn hàng",
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Brand

//Rank
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/rank/list",
 * 		tags = {"Rank"},
 * 		summary = "Danh sách các Rank",
 * 		description = "Danh sách các Rank",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/rank/create",
 * 		tags = {"Rank"},
 * 		summary = "Tạo mới Rank",
 * 		description = "Tạo mới Rank",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Mô tả của rank",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="coefficient",
 *          description="Hệ số k",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/rank/detail",
 * 		tags = {"Rank"},
 * 		summary = "Chi tiết rank",
 * 		description = "Chi tiết rank",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/rank/update",
 * 		tags = {"Rank"},
 * 		summary = "Cập nhật Rank",
 * 		description = "Cập nhật Rank",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="Mô tả của rank",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="coefficient",
 *          description="Hệ số k",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/rank/delete",
 * 		tags = {"Rank"},
 * 		summary = "Xóa Rank",
 * 		description = "xóa Rank",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các Rank"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Brand

//Store
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/store/list",
 * 		tags = {"Store"},
 * 		summary = "Danh sách các cửa hàng",
 * 		description = "Danh sách các cửa hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="brand_id",
 *          description="Id của nhãn hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "sub_brand_id[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của nhãn con"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/store/create",
 * 		tags = {"Store"},
 * 		summary = "Tạo mới cửa hàng",
 * 		description = "Tạo mới cửa hàng",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="representative",
 *          description="Người đại diện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="role",
 *          required=true,
 *          in="query",
 *          description="Vai trò (Đang chỉ có: 1 => admin)",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="profile_store_image",
 *          required=false,
 *          in="query",
 *          description="File ảnh của cửa hàng",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "brands[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "idBrand",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "idsSubBrand",
 *                     type="array",
 *			            @OA\Items(
 * 				            type = "integer",
 *                      ),
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của nhãn con và nhãn hàng nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/store/detail",
 * 		tags = {"Store"},
 * 		summary = "Chi tiết cửa hàng",
 * 		description = "Chi tiết cửa hàng",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/store/update",
 * 		tags = {"Store"},
 * 		summary = "Cập nhật cửa hàng",
 * 		description = "Cập nhật cửa hàng",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="representative",
 *          description="Người đại diện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=false,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="role",
 *          required=true,
 *          in="query",
 *          description="Vai trò (Đang chỉ có: 1 => admin)",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="profile_store_image",
 *          required=false,
 *          in="query",
 *          description="File ảnh của cửa hàng",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "brands[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "idBrand",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "idsSubBrand",
 *                     type="array",
 *			            @OA\Items(
 * 				            type = "integer",
 *                      ),
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của nhãn con và nhãn hàng nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/store/delete",
 * 		tags = {"Store"},
 * 		summary = "Xóa cửa hàng",
 * 		description = "xóa cửa hàng",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của cửa hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/store/change-status-store",
 * 		tags = {"Store"},
 * 		summary = "Thay đổi trạng thái của cửa hàng",
 * 		description = "Thay đổi trạng thái của cửa hàng",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="is_active",
 *          description="Trạng thái chuyển đổi (1 => active, 0 => disable)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/store/get-all-brand-store-selected",
 * 		tags = {"Store"},
 * 		summary = "Lấy tất cả nhãn hàng trong 1 cửa hàng",
 * 		description = "Lấy tất cả nhãn hàng trong 1 cửa hàng",
 *     @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Store

//Collections
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/collection/list-collection",
 * 		tags = {"Admin Collection"},
 * 		summary = "Danh sách bộ sưu tập",
 * 		description = "Danh sách bộ sưu tập",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/collection/list-media",
 * 		tags = {"Admin Collection"},
 * 		summary = "Danh sách media",
 * 		description = "Danh sách media",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="owner_id",
 *          description="Id của bộ sưu tập",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="loại media ( 1 là ảnh , 2 là video)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/collection/create-collection",
 * 		tags = {"Admin Collection"},
 * 		summary = "Thêm mới vào bộ sưu tập",
 * 		description = "hêm mới vào bộ sưu tập",
 *      @OA\Parameter(
 *          name="customer_account_id",
 *          description="Id của khách hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="name",
 *          description="Tên của khách hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "files[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "file",
 * 			   ),
 *          ),
 * 			description = "Danh sách các file upload"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/collection/create-media",
 * 		tags = {"Admin Collection"},
 * 		summary = "Thêm mới vào media",
 * 		description = "hêm mới vào media",
 *      @OA\Parameter(
 *          name="owner_id",
 *          description="Id của bộ sưu tập",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "files[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "file",
 * 			   ),
 *          ),
 * 			description = "Danh sách các file upload"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/collection/delete-collection",
 * 		tags = {"Admin Collection"},
 * 		summary = "Xóa bộ sưu tập",
 * 		description = "Xóa bộ sưu tập",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của Bộ sưu tập"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/collection/delete-media",
 * 		tags = {"Admin Collection"},
 * 		summary = "Xóa media",
 * 		description = "Xóa media",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của Bộ sưu tập"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End collections

//Branch
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/branch/list",
 * 		tags = {"Branch"},
 * 		summary = "Danh sách các chi nhánh",
 * 		description = "Danh sách các chi nhánh",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="brand_id",
 *          description="Id của nhãn hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_id",
 *          description="Mã cửa hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "sub_brand_id[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của các nhãn con"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/branch/create",
 * 		tags = {"Branch"},
 * 		summary = "Tạo mới chi nhánh",
 * 		description = "Tạo mới chi nhánh",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="contact",
 *          description="Người liên hệ",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại liên hệ",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="address",
 *          description="Địa chỉ",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Quận/Huyện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Tỉnh/Thành phố",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="rank_id",
 *          description="Id của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "idsBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/branch/detail",
 * 		tags = {"Branch"},
 * 		summary = "Chi tiết chi nhánh",
 * 		description = "Chi tiết chi nhánh",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/branch/update",
 * 		tags = {"Branch"},
 * 		summary = "Cập nhật chi nhánh",
 * 		description = "Cập nhật chi nhánh",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="contact",
 *          description="Người liên hệ",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại liên hệ",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="address",
 *          description="Địa chỉ",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Quận/Huyện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Tỉnh/Thành phố",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="rank_id",
 *          description="Id của rank",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "idsBrand[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của nhãn hàng"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/branch/delete",
 * 		tags = {"Branch"},
 * 		summary = "Xóa chi nhánh",
 * 		description = "xóa chi nhánh",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của chi nhánh muốn xóa"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Branch

//Device
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/device/list",
 * 		tags = {"Device"},
 * 		summary = "Danh sách các thiết bị",
 * 		description = "Danh sách các thiết bị",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Trạng thái ( 1 => kết nối, 2 => mất kết nối, 3 => không hoạt động)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          description="Chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_id",
 *          description="Id cửa hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="own",
 *          description="Thuộc quyền sở hữu của ant ( 1 là thuộc ant)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/device/create",
 * 		tags = {"Device"},
 * 		summary = "Tạo mới thiết bị",
 * 		description = "Tạo mới thiết bị",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="mô tả thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="own",
 *          description="Bên sở hữu thiết bị (1 => thuộc quyên sở hữu ,2 => thuộc cửa hàng)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_id",
 *          required=true,
 *          in="query",
 *          description="Thuộc cửa hàng",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          required=true,
 *          in="query",
 *          description="Thuộc chi nhánh",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/device/detail",
 * 		tags = {"Device"},
 * 		summary = "Chi tiết thiết bị",
 * 		description = "Chi tiết thiết bị",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/device/update",
 * 		tags = {"Device"},
 * 		summary = "Cập nhật thiết bị",
 * 		description = "Cập nhật thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="description",
 *          description="mô tả thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="own",
 *          description="Bên sở hữu thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_id",
 *          required=true,
 *          in="query",
 *          description="Thuộc cửa hàng",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          required=true,
 *          in="query",
 *          description="Thuộc chi nhánh",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/device/delete",
 * 		tags = {"Device"},
 * 		summary = "Xóa thiết bị",
 * 		description = "xóa thiết bị",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của thiết bị"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/device/change-status-device",
 * 		tags = {"Device"},
 * 		summary = "Thay đổi trạng thái của thiết bị",
 * 		description = "Thay đổi trạng thái của thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="is_active",
 *          description="Trạng thái thiết bị̣ (1 => active, 0 => disable)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="status",
 *          description="Trạng thái chạy của thiết bị (1 => active, 0 => disable)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/device/detail-collection",
 * 		tags = {"Device"},
 * 		summary = "Chi tiết thiết bị với collection",
 * 		description = "Chi tiết thiết bị với collection",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/device/add-collection",
 * 		tags = {"Device"},
 * 		summary = "Thê mới collection vào thiết bị",
 * 		description = "Thê mới collection vào thiết bị",
 *     @OA\Parameter(
 *          name="Id",
 *          description="Id thiếu bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "type",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách collection thêm mới"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/device/update-collection",
 * 		tags = {"Device"},
 * 		summary = "Cập nhật vị trú của collection trong thiết bị",
 * 		description = "Cập nhật vị trú của collection trong thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "admin_device_image_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách thay đổi vị trí collection"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/admin/device/delete-collection",
 * 		tags = {"Device"},
 * 		summary = "Xóa collection khỏi thiết bị",
 * 		description = "Xóa collection khỏi thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của bảng liên kết cần xóa"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Device

//Start Order Admin
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các cửa hàng mình quảng cáo chéo",
 * 		description = "Danh sách các cửa hàng mình quảng cáo chéo",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Trạng thoái đơn hàng (1 => Đang chờ , 2 => Đã xác nhận , 3 => Đã hoàn thành, 4 => Đã từ chối , 5 => Đã bị hủy)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Thời gian bắt đầu tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Thời gian kết thúc tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list-store-detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các cửa hàng trong một đơn hàng",
 * 		description = "Danh sách các cửa hàng trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Chi tiết đơn hàng",
 * 		description = "Chi tiết đơn hàng",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list-branch-detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các chi nhánh trong một đơn hàng",
 * 		description = "Danh sách các chi nhánh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/get-store-and-branch-detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Chi tiết cửa hàng",
 * 		description = "Chi tiết cửa hàng",
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list-collection-detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các ảnh trong một đơn hàng",
 * 		description = "Danh sách các ảnh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của dơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list-device-detail-order",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các thiết bị trong một đơn hàng",
 * 		description = "Danh sách các thiết bị trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/admin/order/list-collection-detail-order-waiting",
 * 		tags = {"Admin Order"},
 * 		summary = "Danh sách các ảnh trong một đơn hàng",
 * 		description = "Danh sách các ảnh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/admin/order/accept-request",
 * 		tags = {"Admin Order"},
 * 		summary = "Cập nhật trạng thái đơn hàng",
 * 		description = "Cập nhật trạng thái đơn hàng",
 *     @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng̣",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="type",
 *          description="Trạng thái cập nhật (1 là chấp nhận đơn hàng , 2 là huy đơn hàng)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="reason",
 *          description="Lý do từ chối",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Order Admin


//STORE
//Store account
/**
 * @OA\Post(
 * 		path = "/api/v1/store/sign-in",
 * 		tags = {"Store Account"},
 * 		summary = "Đăng nhập tài khoản",
 * 		description = "Đăng nhập với tư cách là Admin vào ứng dụng",
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/sign-out",
 * 		summary="Đăng xuất tài khoản",
 * 		tags={"Store Account"},
 * 		description="Đăng xuất tài khoản Admin khỏi ứng dụng",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/account/detail",
 * 		tags = {"Store Account"},
 * 		summary = "Chi tiết tài khoản",
 * 		description = "Chi tiết tài khoản",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của người dùng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/account/update",
 * 		tags = {"Store Account"},
 * 		summary = "Cập nhật tài khoản",
 * 		description = "Cập nhật tài khoản",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="username",
 *          description="Tên tài khoản",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="password",
 *          description="Mật khẩu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="profile_image",
 *          required=false,
 *          in="query",
 *          description="File ảnh của tài khoản",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/create-new-password",
 * 		tags = {"Store Account"},
 * 		summary = "Gửi mail nhận link reset mật khẩu",
 * 		description = "Gửi mail nhận link reset mật khẩu",
 *      @OA\Parameter(
 *          name="email",
 *          required=true,
 *          in="query",
 *          description="Email",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/reset-password",
 * 		tags = {"Store Account"},
 * 		summary = "Xác nhận vào form đổi mật khẩu",
 * 		description = "Xác nhận vào form đổi mật khẩu",
 *      @OA\Parameter(
 *          name="token",
 *          description="Token",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/update-new-password",
 * 		tags = {"Store Account"},
 * 		summary = "Thay đổi mật khẩu mới",
 * 		description = "Thay đổi mật khẩu mới",
 *      @OA\Parameter(
 *          name="token",
 *          required=true,
 *          in="query",
 *          description="Token",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */

//End store account

//Dashboard
/**
 * @OA\Get(
 * 		path = "/api/v1/store/dashboard/homepage",
 * 		tags = {"Dashboard"},
 * 		summary = "Thống kê chung",
 * 		description = "Thống kê chung",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/dashboard/get-list-store-ads",
 * 		tags = {"Dashboard"},
 * 		summary = "Danh sách cửa hàng đã book chéo",
 * 		description = "Danh sách cửa hàng đã book chéo",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/dashboard/get-list-branch-ads",
 * 		tags = {"Dashboard"},
 * 		summary = "Danh sách chi nhánh đã book chéo",
 * 		description = "Danh sách chi nhánh đã book chéo",
 *     @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/dashboard/media-playback-statistics",
 * 		tags = {"Dashboard"},
 * 		summary = "Danh sách media phát lại",
 * 		description = "Danh sách media phát lại",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          description="Id của chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu quảng cáo",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc quảng cáo",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Dashboard

//Group store account
/**
 * @OA\Get(
 * 		path = "/api/v1/store/group-store-account/all-group-store-accounts",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Danh sách các nhóm tài khoản",
 * 		description = "Danh sách các nhóm tài khoản",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/group-store-account/list",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Danh sách các nhóm tài khoản",
 * 		description = "Danh sách các nhóm tài khoản",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/group-store-account/create",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Tạo mới nhóm tài khoản",
 * 		description = "Tạo mới nhóm tài khoản",
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của nhóm tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "permissions[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "permission_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "view",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "add",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "edit",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "delete",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của permission và hoạt đông (Theo thứ tự là 1 (Xem), 2 (Thêm), 3 (Chỉnh sửa), 4 (Xóa))"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/group-store-account/detail",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Chi tiết nhóm tài khoản",
 * 		description = "Chi tiết nhóm tài khoản",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của nhóm tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/group-store-account/update",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Cập nhật nhóm tài khoản",
 * 		description = "Cập nhật nhóm tài khoản",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của nhóm tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên của nhóm tài khoản",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "permissions[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "group_store_account_permission_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "permission_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "view",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "add",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "edit",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "delete",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các id của permission và hoạt đông (Theo thứ tự là 1 (Xem), 2 (Thêm), 3 (Chỉnh sửa), 4 (Xóa))"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/store/group-store-account/delete",
 * 		tags = {"GroupStoreAccount"},
 * 		summary = "Xóa nhóm tài khoản",
 * 		description = "Xóa nhóm tài khoản",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của các nhóm tài khoản"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End group store account

//Store sub account
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-account/all-permission",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Danh sách cacs permission",
 * 		description = "Danh sách cacs permission",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-account/list",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Danh sách các tài khoản con của cửa hàng",
 * 		description = "Danh sách các tài khoản con của cửa hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="is_active",
 *          description="Trạng thái (1 là hoạt động, 0 là không hoạt động)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="group_store_account_id",
 *          description="Thuộc nhóm (ID của nhóm tài khoản)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/store-account/create",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Tạo mới tài khoản con của cửa hàng",
 * 		description = "Tạo mới tài khoản con của cửa hàng",
 *     @OA\Parameter(
 *          name="representative",
 *          description="Người đại diện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="group_store_account_id",
 *          required=true,
 *          in="query",
 *          description="Id của nhóm tài khoản",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          required=true,
 *          in="query",
 *          description="Id chi nhánh quản lí",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="make_ads",
 *          required=true,
 *          in="query",
 *          description="Quền tạo quảng cáo (1 => không được quyền, 2 => là được quền)",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-account/detail",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Chi tiết tài khoản con của cửa hàng",
 * 		description = "Chi tiết tài khoản con của cửa hàng",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id tài khoản con của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/store-account/update",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Cập nhật tài khoản con của cửa hàng",
 * 		description = "Cập nhật tài khoản con của cửa hàng",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của tài khoản con của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="representative",
 *          description="Người đại diện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="email",
 *          description="Email của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="username",
 *          required=true,
 *          in="query",
 *          description="Tài khoản",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="password",
 *          required=true,
 *          in="query",
 *          description="Mật khẩu",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="group_store_account_id",
 *          required=true,
 *          in="query",
 *          description="Id của nhóm tài khoản",
 *          @OA\Schema(
 *              type="file"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          required=true,
 *          in="query",
 *          description="Id chi nhánh quản lí",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="make_ads",
 *          required=true,
 *          in="query",
 *          description="Quền tạo quảng cáo (1 => không được quyền, 2 => là được quền)",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/store-account/change-make-ads",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Thay đổi trạng thái cho phép quảng cáo của tài khoản",
 * 		description = "Thay đổi trạng thái cho phép quảng cáo của tài khoản",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của tài khoản con của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="make_ads",
 *          required=true,
 *          in="query",
 *          description="Quền tạo quảng cáo (1 => không được quyền, 2 => là được quền)",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/store/store-account/delete",
 * 		tags = {"Store Sub Account"},
 * 		summary = "Xóa tài khoản con",
 * 		description = "Xóa tài khoản con",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của tài khoản con"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End sub store account

//Branches
/**
 * @OA\Get(
 * 		path = "/api/v1/store/branch/all-branches-store",
 * 		tags = {"Store Branch"},
 * 		summary = "Lấy tất cả các chi nhánh thuộc cửa hàng",
 * 		description = "Lấy tất cả các chi nhánh thuộc cửa hàng",
 *      @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="store_account_id",
 *          description="Id của tài khoản cửa hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End branches

//Collections
/**
 * @OA\Get(
 * 		path = "/api/v1/store/collection/list",
 * 		tags = {"Store Collection"},
 * 		summary = "Danh sách bộ sưu tập",
 * 		description = "Danh sách bộ sưu tập",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="loại media ( 1 là ảnh , 2 là video)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/collection/create",
 * 		tags = {"Store Collection"},
 * 		summary = "Thêm mới vào bộ sưu tập",
 * 		description = "hêm mới vào bộ sưu tập",
 *     @OA\Parameter(
 *          name = "files[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "file",
 * 			   ),
 *          ),
 * 			description = "Danh sách các file upload"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/store/collection/delete",
 * 		tags = {"Store Collection"},
 * 		summary = "Xóa bộ sưu tập",
 * 		description = "Xóa bộ sưu tập",
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của Bộ sưu tập"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End collections

//Device
/**
 * @OA\Get(
 * 		path = "/api/v1/store/device/list",
 * 		tags = {"Store Device"},
 * 		summary = "Danh sách các thiết bị",
 * 		description = "Danh sách các thiết bị",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Trạng thái ( 1 => kết nối, 2 => mất kết nối, 3 => không hoạt động)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="branch_id",
 *          description="Chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="own",
 *          description="Thuộc quyền sở hữu của ant ( 1 là thuộc ant)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/device/statistic",
 * 		tags = {"Store Device"},
 * 		summary = "Danh sách thống kê các thiết bị",
 * 		description = "Danh sách thống kê các thiết bị",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="is_export",
 *          description="Export excel (0 => Không export, 1 => export)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/device/add-collection",
 * 		tags = {"Store Device"},
 * 		summary = "Thê mới collection vào thiết bị",
 * 		description = "Thê mới collection vào thiết bị",
 *     @OA\Parameter(
 *          name="Id",
 *          description="Id thiếu bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "type",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "DAnh sách collection thêm mới"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/device/detail",
 * 		tags = {"Store Device"},
 * 		summary = "Chi tiết thiết bị với collection",
 * 		description = "Chi tiết thiết bị với collection",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/device/block-ads",
 * 		tags = {"Store Device"},
 * 		summary = "Cập nhật chặn quảng cáo chiếm dụng từ cửa hàng khác",
 * 		description = "Cập nhật chặn quảng cáo chiếm dụng từ cửa hàng khá",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="time",
 *          description="Thời gian block (Không quá 3 phút và đơn vị tính là giây)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/device/update-collection",
 * 		tags = {"Store Device"},
 * 		summary = "Cập nhật vị trú của collection trong thiết bị",
 * 		description = "Cập nhật vị trú của collection trong thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "store_device_collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách thay đổi vị trí collection"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Delete(
 * 		path = "/api/v1/store/device/delete-collection",
 * 		tags = {"Store Device"},
 * 		summary = "Xóa collection khỏi thiết bị",
 * 		description = "Xóa collection khỏi thiết bị",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "ids[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id của bảng liên kết cần xóa"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Device

//Info Device
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-info/list",
 * 		tags = {"Store info"},
 * 		summary = "Danh sách các chi nhánh của cửa hàng",
 * 		description = "Danh sách các chi nhánh của cửa hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-info/detail-store",
 * 		tags = {"Store info"},
 * 		summary = "Lấy thông tin cửa hàng",
 * 		description = "Lấy thông tin cửa hàng",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/store-info/update-store",
 * 		tags = {"Store info"},
 * 		summary = "Cập nhật cửa hàng",
 * 		description = "Cập nhật cửa hàng",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="address",
 *          description="Địa chỉ",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-info/sidebar-info",
 * 		tags = {"Store info"},
 * 		summary = "Lấy thông tin thanh bar",
 * 		description = "Lấy thông tin thanh bar",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-info/detail-branch",
 * 		tags = {"Store info"},
 * 		summary = "Chi tiết chi nhánh",
 * 		description = "Chi tiết chi nhánh",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/store-info/update-branch",
 * 		tags = {"Store info"},
 * 		summary = "Cập nhật chi nhánh",
 * 		description = "Cập nhật chi nhánh",
 *     @OA\Parameter(
 *          name="id",
 *          description="Id của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="name",
 *          description="Tên chị nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="contact",
 *          description="Người liên hệ",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="phone_number",
 *          description="Số điện thoại chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/store-info/log-operation",
 * 		tags = {"Store info"},
 * 		summary = "Danh sách các lịch sử hoạt đông",
 * 		description = "Danh sách các lịch sử hoạt động",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="ngày kết thúc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "api/v1/store/store-info/log-point",
 * 		tags = {"Store info"},
 * 		summary = "Danh sách các lịch sử điểm",
 * 		description = "Danh sách các lịch sử điểm",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="type",
 *          description="Kiểu lịch sử (1 => Lịch sử tích lũy điểm thưởng , 2 => Lịch sử đổi điểm)",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End info Device

//Order
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các cửa hàng mình quảng cáo chéo",
 * 		description = "Danh sách các cửa hàng mình quảng cáo chéo",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Trạng thoái đơn hàng (1 => Đang chờ , 2 => Đã xác nhận , 3 => Đã hoàn thành, 4 => Đã từ chối , 5 => Đã bị hủy)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Thời gian bắt đầu tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Thời gian kết thúc tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-rank",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các rank",
 * 		description = "Danh sách các rank",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-brand",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các nhãn hàng",
 * 		description = "Danh sách các nhãn hàng",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-store",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các cửa hàng mình có thể quảng cáo chéo",
 * 		description = "Danh sách các cửa hàng mình có thể quảng cáo chéo",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "sub_brand_id[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn hàng con"
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="rank_id",
 *          description="Id của rank",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_time",
 *          description="Thời gian bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_time",
 *          description="Thời gian kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="time_book",
 *          description="Thời gian đặt quảng cáo chéo",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-device",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các thiết bị có thể quảng cáo chéo",
 * 		description = "Danh sách các thiết bị có thể quảng cáo chéo",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "sub_brand_id[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn hàng con"
 *      ),
 *     @OA\Parameter(
 *          name="store_id",
 *          description="Id của cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="rank_id",
 *          description="Id của rank",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_time",
 *          description="Thời gian bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_time",
 *          description="Thời gian kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="time_book",
 *          description="Thời gian đặt quảng cáo chéo",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/info-branch-or-store",
 * 		tags = {"Order"},
 * 		summary = "Thông tin cửa hàng hoặc chi nhánh cho đơn hàng",
 * 		description = "Thông tin cửa hàng hoặc chi nhánh cho đơn hàng",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/order/save-order",
 * 		tags = {"Order"},
 * 		summary = "Lưu đơn quảng cáo chéo",
 * 		description = "Lưu đơn quảng cáo chéo",
 *     @OA\Parameter(
 *          name="payment",
 *          description="Số điểm phải trả để thuê quảng cáo chéo",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="note",
 *          description="Ghi trú của đơn hàng",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "ids_device[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách id thiết bị"
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "type",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách collection thêm mới"
 *      ),
 *     @OA\Parameter(
 *          name = "timeframes[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "start_date",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property = "end_date",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property = "start_time",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property = "end_time",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property = "frequency",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "total",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách các khung giờ phát quảng cáo"
 *      ),
 *     @OA\Parameter(
 *          name = "sub_brand_id[]",
 * 			required = false,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "integer",
 * 			   ),
 *          ),
 * 			description = "Danh sách các nhãn hàng con"
 *      ),
 *     @OA\Parameter(
 *          name="province_id",
 *          description="Id của tỉnh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="district_id",
 *          description="Id của huyện",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="rank_id",
 *          description="Id của rank",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_date",
 *          description="Ngày bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_date",
 *          description="Ngày kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="start_time",
 *          description="Thời gian bắt đầu",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="end_time",
 *          description="Thời gian kết thúc",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="time_book",
 *          description="Thời gian đặt quảng cáo chéo",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="type_booking",
 *          description="Kiểu đặt hàng ( 1 = > Chọn tập tin, 2 => Ước lượng thời gian )",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-store-detail-order",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các cửa hàng trong một đơn hàng",
 * 		description = "Danh sách các cửa hàng trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/detail-order",
 * 		tags = {"Order"},
 * 		summary = "Chi tiết đơn hàng",
 * 		description = "Chi tiết đơn hàng",
 *      @OA\Parameter(
 *          name="id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-branch-detail-order",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các chi nhánh trong một đơn hàng",
 * 		description = "Danh sách các chi nhánh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/get-store-and-branch-detail-order",
 * 		tags = {"Order"},
 * 		summary = "Chi tiết cửa hàng",
 * 		description = "Chi tiết cửa hàng",
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-collection-detail-order",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các ảnh trong một đơn hàng",
 * 		description = "Danh sách các ảnh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-device-detail-order",
 * 		tags = {"Order"},
 * 		summary = "Danh sách các thiết bị trong một đơn hàng",
 * 		description = "Danh sách các thiết bị trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_store_id",
 *          description="Id của đơn hàng cửa hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/order/list-collection-order",
 * 		tags = {"Order"},
 * 		summary = "Danh sách collection trong một đơn hàng",
 * 		description = "Danh sách các collection trong một đơn hàng",
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/store/order/update-collection-order",
 * 		tags = {"Order"},
 * 		summary = "Cập nhật collection vào đơn hàng",
 * 		description = "Cập nhật collection vào đơn hàng",
 *     @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng̣",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name = "collections[]",
 * 			required = true,
 * 			in = "query",
 *          @OA\Schema(
 *              type="array",
 *			    @OA\Items(
 * 				   type = "object",
 *                 @OA\Property(
 *                     property = "store_cross_device_collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "collection_id",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "position",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "second",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "volume",
 *                     type="integer",
 *                 ),
 *                 @OA\Property(
 *                     property = "type",
 *                     type="integer",
 *                 ),
 * 			   ),
 *          ),
 * 			description = "Danh sách collection thêm mới"
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Order

//Start Cross Order
/**
 * @OA\Get(
 * 		path = "/api/v1/store/cross-order/list",
 * 		tags = {"Cross Order"},
 * 		summary = "Danh sách các cửa hàng mình quảng cáo chéo",
 * 		description = "Danh sách các cửa hàng mình quảng cáo chéo",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="status",
 *          description="Trạng thoái đơn hàng (1 => Đang chờ , 2 => Đã xác nhận , 3 => Đã hoàn thành, 4 => Đã từ chối , 5 => Đã bị hủy)",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="start_date",
 *          description="Thời gian bắt đầu tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="end_date",
 *          description="Thời gian kết thúc tìm kiếm",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/cross-order/list-branch-detail-cross-order",
 * 		tags = {"Cross Order"},
 * 		summary = "Danh sách các chi nhánh trong một đơn hàng",
 * 		description = "Danh sách các chi nhánh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/cross-order/get-store-and-branch-detail-cross-order",
 * 		tags = {"Cross Order"},
 * 		summary = "Chi tiết cửa hàng",
 * 		description = "Chi tiết cửa hàng",
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/cross-order/list-collection-detail-cross-order",
 * 		tags = {"Cross Order"},
 * 		summary = "Danh sách các ảnh trong một đơn hàng",
 * 		description = "Danh sách các ảnh trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/store/cross-order/list-device-detail-cross-order",
 * 		tags = {"Cross Order"},
 * 		summary = "Danh sách các thiết bị trong một đơn hàng",
 * 		description = "Danh sách các thiết bị trong một đơn hàng",
 *     @OA\Parameter(
 *          name="page",
 *          description="Số của trang muốn lấy dữ liệu",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="limit",
 *          description="Số bản ghi cần lấy",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          description="Cột sắp xếp: id, name..",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="direction",
 *          description="Kiểu sắp xếp: desc, asc",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="key_word",
 *          description="Nhập từ khóa",
 *          required=false,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_id",
 *          description="Id của đơn hàng",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="order_branch_id",
 *          description="Id của đơn hàng chi nhánh",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
//End Cross Order

//Start App
/**
 * @OA\Post(
 * 		path = "/api/v1/app/sign-in",
 * 		tags = {"App"},
 * 		summary = "Đăng nhập vào thiết bị",
 * 		description = "Đăng nhập vào thiết bị",
 *     @OA\Parameter(
 *          name="device_code",
 *          description="Mã của thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="active_code",
 *          description="Mã thiết bị trên hệ thống",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="model",
 *          description="Đối tương thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="width",
 *          description="Chiều rộng màn hình thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="height",
 *          description="Chiều cao màn hình thiết bị",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="size",
 *          description="Kích thước màn hình",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *     @OA\Parameter(
 *          name="os",
 *          description="Hệ điều hành",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Get(
 * 		path = "/api/v1/app/download",
 * 		tags = {"App"},
 * 		summary = "Download media cho thiết bị",
 * 		description = "Download media cho thiết bị",
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/app/check-status-collection-download",
 * 		tags = {"App"},
 * 		summary = "Check những medio đã download thành công",
 * 		description = "Check những medio đã download thành công",
 *     @OA\Parameter(
 *          name="data",
 *          description="Dữ liệu thống kê",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
/**
 * @OA\Post(
 * 		path = "/api/v1/app/statistics",
 * 		tags = {"App"},
 * 		summary = "Lưu dữ liệu thống kê",
 * 		description = "Lưu dữ liệu thống kê",
 *     @OA\Parameter(
 *          name="data",
 *          description="Dữ liệu thống kê",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      security={
 *         {
 *             "Bearer": {}
 *         }
 *      },
 * 		@OA\Response(response=200,description="Success"),
 * 		@OA\Response(response=400,description="Bad request"),
 * 		@OA\Response(response=404,description="Page Not Found"),
 * 		@OA\Response(response=500,description="System Error")
 * )
 */
class SwaggerController extends Controller
{


}
