
//インポート
import java.io.IOException;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.List;
import java.util.stream.Stream;

public class Test13 {
	public static void main(String[] args) throws IOException {

		//引数の設定がされていないときのみ表示
		if (args.length < 4) {
			System.out.println("【パラメータ未設定エラー】");
			System.out.println("検索したいフォルダ、出力するフォルダ、検索する文字列、置き換える文字列を指定してください");
			return;
		}

		System.out.println("-------------------------------------------------------------------");
		System.out.println("\"" + args[1] + "\"が存在する【.txtファイル】のみ\"" + args[2] + "\"に置換し、");
		System.out.println("【置換後】フォルダに対象のファイルを出力します。");
		System.out.println("-------------------------------------------------------------------");
		System.out.println();

		//入力パスと出力パス、検索と置換文字列を指定
		System.out.println("指定されたパラメータ");

		//置換前のフォルダパスを指定
		Path inputFolderPath = Paths.get(args[0]);
		System.out.println("【パラメータ1：ディレクトリのパス】：" + inputFolderPath);

		//検索文字列
		String searchStr = args[1];
		System.out.println("【パラメータ2：検索文字列】：" + searchStr);

		//置換文字列
		String replaceStr = args[2];
		System.out.println("【パラメータ3：置換文字列】：" + replaceStr);

		//コピー先フォルダー　置換後
		Path outputFolderPath = Paths.get(args[3]);
		System.out.println("【パラメータ4：出力先ディレクトリのパス】：" + outputFolderPath);

		//空行追加
		System.out.println();

		//フォルダが存在しなければ outputFolderPath を作成 
		if (!Files.exists(outputFolderPath)) {
			Files.createDirectories(outputFolderPath);
			System.out.println("----------------------------------");
			System.out.println("フォルダ：【置換後】を作成しました");
			System.out.println("----------------------------------");
		}

		//空行追加
		System.out.println();

		//Path オブジェクトが表すディレクトリの中に含まれるファイルやディレクトリの一覧をサブディレクトリの中まで含めて取得
		try (Stream<Path> stream = Files.walk(inputFolderPath)) {

			System.out.println("置換処理を実行しています");

			/*
			 * isRegularFile ファイルのみを対象(通常のファイルであるか判定)
			 * ディレクトリファイルやデバイスファイルであった場合はfalse
			 */
			stream.filter(Files::isRegularFile)
					// ファイル名の最後に ○○.txt の名称がつけられている対象のみ
					.filter(path -> path.toString().endsWith(".txt")).forEach(inputFile -> {
						try {
							//String型 のリストを作成(readAllLines で全ての行を読み取る) ファイルの内容を読み込み 読み込み対象の指定は UTF_8
							List<String> lines = Files.readAllLines(inputFile, StandardCharsets.UTF_8);

							//検索文字列が含まれているかチェック
							boolean targetWord = lines.stream().anyMatch(line -> line.contains(searchStr));

							if (targetWord) {

								for (int i = 0; i < lines.size(); i++) {
									//replace(置換される文字列(あいうえお), 置換する文字列(かきくけこ))
									lines.set(i, lines.get(i).replace(searchStr, replaceStr));
								}

								//指定されたパスと同じファイルを見つける 相対パスを作成
								Path relativePath = inputFolderPath.relativize(inputFile);
								//2つの Pathを結合 
								Path outputFile = outputFolderPath.resolve(relativePath);

								//置換後の内容を出力ファイルに書き込む
								Files.write(outputFile, lines, StandardCharsets.UTF_8);
								//処理終了メッセージ
								System.out.println("【" + inputFile.getFileName() + "】を処理しました");
							}
						} catch (IOException e) {
							e.printStackTrace();
						}
					});
		} catch (IOException e) {
			e.printStackTrace();
		}

		//空行追加
		System.out.println();
		System.out.println("------------------------");
		System.out.println("全ての処理が完了しました");
		System.out.println("------------------------");
	}
}
