import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

//[kuromoji]のインポート
import com.atilika.kuromoji.ipadic.Token;
import com.atilika.kuromoji.ipadic.Tokenizer;

public class Test17 {
	public static void main(String[] args) {

		//解析対象を変数 momotarou に代入
		String momotarou = "むかしむかし、あるところに、おじいさんとおばあさんが住んでいました。"
				+ "おじいさんは山へしばかりに、おばあさんは川へせんたくに行きました。"
				+ "おばあさんが川でせんたくをしていると、ドンブラコ、ドンブラコと、大きな桃が流れてきました。"
				+ "「おや、これは良いおみやげになるわ」"
				+ "おばあさんは大きな桃をひろいあげて、家に持ち帰りました。"
				+ "そして、おじいさんとおばあさんが桃を食べようと桃を切ってみると、なんと中から元気の良い男の赤ちゃんが飛び出してきました。"
				+ "「これはきっと、神さまがくださったにちがいない」"
				+ "子どものいなかったおじいさんとおばあさんは、大喜びです。"
				+ "桃から生まれた男の子を、おじいさんとおばあさんは桃太郎と名付けました。"
				+ "桃太郎はスクスク育って、やがて強い男の子になりました。"
				+ "そしてある日、桃太郎が言いました。"
				+ "「ぼく、鬼ヶ島(おにがしま)へ行って、わるい鬼を退治します」"
				+ "おばあさんにきび団子を作ってもらうと、鬼ヶ島へ出かけました。"
				+ "旅の途中で、イヌに出会いました。"
				+ "「桃太郎さん、どこへ行くのですか？」"
				+ "「鬼ヶ島へ、鬼退治に行くんだ」"
				+ "「それでは、お腰に付けたきび団子を１つ下さいな。おともしますよ」"
				+ "イヌはきび団子をもらい、桃太郎のおともになりました。"
				+ "そして、こんどはサルに出会いました。"
				+ "「桃太郎さん、どこへ行くのですか？」"
				+ "「鬼ヶ島へ、鬼退治に行くんだ」"
				+ "「それでは、お腰に付けたきび団子を１つ下さいな。おともしますよ」"
				+ "そしてこんどは、キジに出会いました。"
				+ "「桃太郎さん、どこへ行くのですか？」"
				+ "「鬼ヶ島へ、鬼退治に行くんだ」"
				+ "「それでは、お腰に付けたきび団子を１つ下さいな。おともしますよ」"
				+ "こうして、イヌ、サル、キジの仲間を手に入れた桃太郎は、ついに鬼ヶ島へやってきました。"
				+ "ももたろう"
				+ "鬼ヶ島では、鬼たちが近くの村からぬすんだ宝物やごちそうをならべて、酒盛りの真っ最中です。"
				+ "「みんな、ぬかるなよ。それ、かかれ！」"
				+ "イヌは鬼のおしりにかみつき、サルは鬼のせなかをひっかき、キジはくちばしで鬼の目をつつきました。"
				+ "そして桃太郎も、刀をふり回して大あばれです。"
				+ "とうとう鬼の親分が、"
				+ "「まいったぁ、まいったぁ。こうさんだ、助けてくれぇ」"
				+ "と、手をついてあやまりました。"
				+ "桃太郎とイヌとサルとキジは、鬼から取り上げた宝物をくるまにつんで、元気よく家に帰りました。"
				+ "おじいさんとおばあさんは、桃太郎の無事な姿を見て大喜びです。"
				+ "そして三人は、宝物のおかげでしあわせにくらしましたとさ。"
				+ "おしまい";

		//kuromoji のトークナイザーを作成
		Tokenizer tokenizer = new Tokenizer();

		//String型のキー、int型の値 の名詞の出現回数を記録するMapを作成(ラムダ式仕様)
		Map<String, Integer> wordCountMap = tokenizer.tokenize(momotarou)
				.stream()
				// "名詞" のみを対象に
				.filter(token -> token.getPartOfSpeechLevel1().equals("名詞"))
				/*名詞の表層形をキーに
				 *初期値は 1 で同じ名詞があればカウントを加算
				 */
				.collect(Collectors.toMap(Token::getSurface, token -> 1, Integer::sum));
		
		//出現回数を名詞でソートしてランキングを作成
		List<Map.Entry<String, Integer>> rankingList = wordCountMap.entrySet()
				
				.stream()

				.sorted((entry1, entry2) -> entry2.getValue().compareTo(entry1.getValue()))

				.collect(Collectors.toList());

		//ランキング結果を出力
		System.out.println("結果は");

		//現在の順位(ラムダ式で使うため final int[]に 変更)
		final int[] rank = { 1 };
		//出力用の順位
		final int[] displayRank = { 1 };
		//前の出現回数の保存用
		final int[] previousCount = { -1 };

		rankingList.forEach(entry -> {

			String word = entry.getKey();
			int count = entry.getValue();

			//前回の出現回数と異なれば、順位を更新
			if (count != previousCount[0]) {

				displayRank[0] = rank[0];
			}
			//出力
			System.out.println(displayRank[0] + "位：\"" + word + "\"が" + count + "回");

			//次のループ準備
			previousCount[0] = count;
			//全体の順位は常にインクリメント(変化)させる
			rank[0]++;

		});
	}
}