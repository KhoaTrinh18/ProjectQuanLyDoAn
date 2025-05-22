<p>Đề tài "<strong>{{ $deTai->ten_de_tai }}</strong>" ({{ \Carbon\Carbon::parse($ngay)->format('d-m-Y') }}) cần được chỉnh sửa trước khi duyệt, với nội dung chỉnh sửa như sau: </p>

<p>{{ $noiDungSua }}</p>

<p>Vui lòng sửa lại đề tài trong thời gian quy định.</p>

<p>Trân trọng,<br>Ban quản lý đề tài</p>